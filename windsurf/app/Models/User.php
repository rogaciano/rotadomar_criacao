<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Localizacao;
use App\Models\Group;
use App\Models\Permission;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
        
        // Verifica se o usuário tem a permissão através de seus grupos
        foreach ($this->groups as $group) {
            foreach ($group->permissions as $permission) {
                if ($permission->slug === $permissionSlug) {
                    return true;
                }
            }
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
}
