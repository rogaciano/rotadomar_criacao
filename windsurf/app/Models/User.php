<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use App\Models\Localizacao;
use App\Models\Group;
use App\Models\Permission;
use App\Models\UserPermission;
use App\Models\UserFilter;
use App\Models\UserAccessSchedule;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'localizacao_id',
        'is_admin',
        'is_faccao',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the localizacao that the user belongs to.
     */
    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class);
    }

    /**
     * Localizações que o usuário pode visualizar (além da principal)
     */
    public function visualizacoes()
    {
        return $this->belongsToMany(Localizacao::class, 'user_localizacao_visualizacao');
    }

    /**
     * Verifica se o usuário é um usuário de facção (empresa externa).
     * Usuários de facção têm acesso restrito apenas à sua localização principal.
     * O campo is_faccao deve ser marcado explicitamente pelo administrador.
     *
     * @return bool
     */
    public function isUsuarioFaccao(): bool
    {
        if ($this->isAdmin() || empty($this->localizacao_id)) {
            return false;
        }

        return (bool) $this->is_faccao;
    }

    /**
     * Verifica se o usuário é um usuário de localização (facção/setor) restrito
     * Alias para isUsuarioFaccao() para manter compatibilidade
     *
     * @return bool
     */
    public function isUsuarioLocalizacao(): bool
    {
        return $this->isUsuarioFaccao();
    }

    /**
     * Verifica se o usuário pode gerenciar etapas de uma localização específica
     * Permite APENAS se for a localização PRINCIPAL do usuário de facção
     * Admins e outros usuários NÃO podem mudar etapas
     *
     * @param int $localizacaoId
     * @return bool
     */
    public function podeGerenciarEtapa(int $localizacaoId): bool
    {
        // Apenas usuários de facção podem mudar etapas, e só na sua localização principal
        if (!$this->isUsuarioFaccao()) {
            return false;
        }

        return $this->localizacao_id == $localizacaoId;
    }

    /**
     * Retorna array com IDs das localizações que o usuário pode ver
     * Inclui a localização principal + localizações de visualização
     *
     * @return array
     */
    public function getLocalizacoesPermitidasIds(): array
    {
        $ids = [];

        // Adiciona a localização principal se existir
        if ($this->localizacao_id) {
            $ids[] = $this->localizacao_id;
        }

        // Adiciona as localizações de visualização
        $visualizacaoIds = $this->visualizacoes()->pluck('localizacoes.id')->toArray();
        $ids = array_merge($ids, $visualizacaoIds);

        return array_unique($ids);
    }

    /**
     * Verifica se o usuário é um administrador.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }

    /**
     * Get the groups that the user belongs to.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_group');
    }

    /**
     * Relação com permissões específicas do usuário.
     */
    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    /**
     * Verifica se o usuário tem uma permissão específica.
     *
     * @param string $permissionSlug
     * @return bool
     */
    public function hasPermission($permissionSlug)
    {
        // Administradores têm todas as permissões
        if ($this->isAdmin()) {
            return true;
        }

        $cached = $this->loadPermissionsCache();

        // Verifica permissão direta do usuário
        if (isset($cached['direct'][$permissionSlug])) {
            $up = $cached['direct'][$permissionSlug];
            if ($up['can_create'] || $up['can_read'] || $up['can_update'] || $up['can_delete']) {
                return true;
            }
        }

        // Verifica permissão via grupos
        if (in_array($permissionSlug, $cached['group_permissions'] ?? [], true)) {
            return true;
        }

        return false;
    }

    /**
     * Verifica se o usuário tem qualquer uma das permissões especificadas.
     *
     * @param array $permissionSlugs
     * @return bool
     */
    public function hasAnyPermission(array $permissionSlugs)
    {
        foreach ($permissionSlugs as $slug) {
            if ($this->hasPermission($slug)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica se o usuário tem todas as permissões especificadas.
     *
     * @param array $permissionSlugs
     * @return bool
     */
    public function hasAllPermissions(array $permissionSlugs)
    {
        foreach ($permissionSlugs as $slug) {
            if (!$this->hasPermission($slug)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica se o usuário pode executar uma ação específica (create, read, update, delete)
     * para uma permissão (identificada pelo slug).
     */
    public function canAction(string $action, string $permissionSlug): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $action = strtolower($action);
        if (!in_array($action, ['create', 'read', 'update', 'delete'], true)) {
            return false;
        }

        $cached = $this->loadPermissionsCache();

        // Se houver configuração específica do usuário, ela prevalece
        if (isset($cached['direct'][$permissionSlug])) {
            $col = 'can_' . $action;
            return (bool) ($cached['direct'][$permissionSlug][$col] ?? false);
        }

        // Fallback: usa a permissão por grupo
        return $this->hasPermission($permissionSlug);
    }

    /**
     * Carrega e cacheia todas as permissões do usuário.
     * Cache é invalidado pelos Observers ao alterar UserPermission ou Group.
     *
     * @return array{direct: array, group_permissions: array}
     */
    public function loadPermissionsCache(): array
    {
        return Cache::remember("user:{$this->id}:permissions", 3600, function () {
            // Permissões diretas do usuário
            $direct = [];
            $userPerms = $this->userPermissions()->with('permission')->get();
            foreach ($userPerms as $up) {
                if ($up->permission) {
                    $direct[$up->permission->name] = [
                        'can_create' => $up->can_create,
                        'can_read' => $up->can_read,
                        'can_update' => $up->can_update,
                        'can_delete' => $up->can_delete,
                    ];
                }
            }

            // Permissões via grupos
            $groupPermissions = [];
            foreach ($this->groups()->with('permissions')->get() as $group) {
                foreach ($group->permissions as $permission) {
                    $groupPermissions[] = $permission->name;
                }
            }

            return [
                'direct' => $direct,
                'group_permissions' => array_unique($groupPermissions),
            ];
        });
    }

    /**
     * Limpa o cache de permissões deste usuário.
     */
    public function clearPermissionsCache(): void
    {
        Cache::forget("user:{$this->id}:permissions");
    }

    public function canCreate(string $permissionSlug): bool
    {
        return $this->canAction('create', $permissionSlug);
    }

    public function canRead(string $permissionSlug): bool
    {
        return $this->canAction('read', $permissionSlug);
    }

    public function canUpdate(string $permissionSlug): bool
    {
        return $this->canAction('update', $permissionSlug);
    }

    public function canDelete(string $permissionSlug): bool
    {
        return $this->canAction('delete', $permissionSlug);
    }

    /**
     * Relacionamento com os filtros do usuário
     */
    public function filters()
    {
        return $this->hasMany(UserFilter::class);
    }

    /**
     * Salvar filtros para um tipo de página
     *
     * @param string $pageType
     * @param array $filters
     * @return UserFilter
     */
    public function saveFilters(string $pageType, array $filters)
    {
        return UserFilter::saveFilters($this->id, $pageType, $filters);
    }

    /**
     * Obter filtros para um tipo de página
     *
     * @param string $pageType
     * @return array
     */
    public function getFilters(string $pageType)
    {
        return UserFilter::getFilters($this->id, $pageType);
    }

    /**
     * Limpar filtros para um tipo de página
     *
     * @param string $pageType
     * @return bool
     */
    public function clearFilters(string $pageType)
    {
        return UserFilter::clearFilters($this->id, $pageType);
    }

    /**
     * Get the access schedule associated with the user.
     */
    public function accessSchedule()
    {
        return $this->hasOne(UserAccessSchedule::class, 'user_id');
    }

    /**
     * Obter contagem de movimentações pendentes (não concluídas) para a localização do usuário
     *
     * @return int
     */
    public function getMovimentacoesPendentesCount()
    {
        if (!$this->localizacao_id) {
            return 0;
        }

        return \App\Models\Movimentacao::where('localizacao_id', $this->localizacao_id)
            ->where('concluido', false)
            ->whereNull('data_saida')
            ->count();
    }

    /**
     * Obter contagem de movimentações atrasadas para a localização do usuário
     *
     * @return int
     */
    public function getMovimentacoesAtrasadasCount()
    {
        if (!$this->localizacao_id || !$this->localizacao) {
            return 0;
        }

        // Se a localização não tem prazo definido, não há movimentações atrasadas
        if (!$this->localizacao->prazo) {
            return 0;
        }

        // Buscar movimentações pendentes e calcular dias úteis
        $movimentacoes = \App\Models\Movimentacao::where('localizacao_id', $this->localizacao_id)
            ->where('concluido', false)
            ->whereNull('data_saida')
            ->get();

        // Contar apenas as que estão atrasadas considerando dias úteis
        return $movimentacoes->filter(function ($movimentacao) {
            $diasUteis = \App\Helpers\MovimentacaoHelper::calcularDiasUteis($movimentacao->data_entrada);
            return $diasUteis > $this->localizacao->prazo;
        })->count();
    }

    /**
     * Obter movimentações pendentes completas para a localização do usuário
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMovimentacoesPendentes()
    {
        if (!$this->localizacao_id) {
            return collect();
        }

        return \App\Models\Movimentacao::with(['produto', 'tipo', 'situacao', 'localizacao'])
            ->where('localizacao_id', $this->localizacao_id)
            ->where('concluido', false)
            ->whereNull('data_saida')
            ->orderByRaw('DATEDIFF(NOW(), data_entrada) DESC')
            ->get();
    }
}
