<?php
namespace feature\Controllers;

use App\Services\UserService;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

final class GymTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $DBGroup = 'tests';
    protected $namespace = null;
    protected $seed = 'Tests\Support\Database\Seeds\MasterSeeder';

    // Variable pour stocker un admin créé
    private $admin;
    private $user;

    private $auth;

    //Fonction SetUp qui sera relancée avant chaque test
    protected function setUp(): void
    {
        parent::setUp();
        // initialisation du système d'authentification de shield
        $this->auth = auth('session')->getAuthenticator();

        //Récupération du model nécessaire
        $model = auth()->getProvider();

        //Création de l'utilisateur
        $admin = new \CodeIgniter\Shield\Entities\User([
            'username' => 'admintest',
            'active' => 1,
        ]);
        $admin->setEmail('admin@test.fr');
        $admin->setPassword('admin123');

        //Sauvegarde de l'utilisateur
        $model->save($admin);

        // Récupérer l'ID généré après la sauvegarde
        $adminId = $model->getInsertID();

        //Création du profil lié à l'utilisateur
        $profileModel = model('UserProfileModel');
        $profileModel->insert([
            'user_id' => $adminId,
        ]);

        //Recharge l'utilisateur et lui attribue le rôle d'admin
        $this->admin = $model->findById($adminId);
        $this->admin->addGroup('admin');

        //Création d'un utilisateur classique
        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => 'usertest',
            'active' => 1,
        ]);
        $user->setEmail('user@test.fr');
        $user->setPassword('user1234');
        $model->save($user);
        $userId = $model->getInsertID();
        $profileModel->insert([
            'user_id' => $userId,
        ]);
        $this->user = $model->findById($userId);
        $this->user->addGroup('user');
    }

    /**
     * Test 1 : Vérifier que la page liste des gymnases charge correctement
     * pour un admin connecté
     */
    public function testGymIndexPageLoadsForAdmin()
    {
        //simule une session en tant qu'admin
        auth()->login($this->admin);
        //tente d'afficher la page index
        $result = $this->get('admin/gym');

        $result->assertStatus(200);
        $result->assertSee('gymnases');
    }

    /**
     * Test 2 : Vérifier que la page liste des gymnases est bloquée
     * pour un utilisateur classique connecté
     */
    public function testGymIndexPageLoadsForUser()
    {
        //simule une session en tant qu'user
        auth()->login($this->user);
        //tente d'afficher la page index
        $result = $this->get('admin/gym');

        $result->assertStatus(302);
        $result->assertRedirectTo('http://localhost:8080');
    }


    /**
     * Test 3 : Vérifier que la recherche d'un gymnase fonctionne pour un utilisateur connecté
     */
    public function testCanSearchGym(){
        auth()->login($this->admin);

        $result = $this->post('datatable/searchdatatable',[
            'draw' => '1',
            'start' => '0',
            'length' => '10',
            'model' => 'GymModel',
            'search' => [
                'value' => 'bouche',
                'regex' => 'false'
            ],
            'order' => [
                [
                    'column' => '1',
                    'dir' => 'asc',
                    'name' => ''
                ]
            ],
            'columns' => [
                [
                    'data' => '',
                    'name' => '',
                    'searchable' => 'false',
                    'orderable' => 'false',
                    'search' => [
                        'value' => '',
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'id',
                    'name' => '',
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => '',
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'fbi_code',
                    'name' => '',
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => '',
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'name',
                    'name' => '',
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => 'bouche',
                        'regex' => 'false'
                    ]
                ],
                [
                    'data' => 'gym_city',
                    'name' => '',
                    'searchable' => 'true',
                    'orderable' => 'true',
                    'search' => [
                        'value' => '',
                        'regex' => 'false'
                    ]
                ],
            ],
        ]);

        $result->assertStatus(200);
        $result->assertSee('JEAN');
        $result->assertDontSee('PINAUD');
    }


}