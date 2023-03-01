<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
				'role'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
		
		
		
		
		
		
		
		public function getAllUsers() {
      
			$result = $this->select('id', 'email', 'name', 'role', 'active', 'created_at', 'updated_at')
				->get()
				->keyBy('id')
				->toArray();
			
			return $result;
    }
		
		
		
		public function updateItem(int $id = 0, array $data = array())
    {
        $data = $this->validateData($data);
        $errors = array();
        if (!(int)$id || empty($data))
            return array(
                'status'    => false,
                'data'      => array(),
                'errors'    => array('Not enough data to update!')
            );
        if ($id == 1) 
            unset($data['roles_id']);
        try {
            $data = $this::where('id', $id)->update($data);
            $status = true;
        } catch (\Illuminate\Database\QueryException $e) {
            $status = false;
            $errors[] = $e->getMessage();
        }
        return array(
            'status'    => $status,
            'data'      => $data,
            'errors'    => $errors
        );
    } 
}