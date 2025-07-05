<?php

namespace App\Console\Commands;

use App\Http\Controllers\ClientController;
use App\Models\Client;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TestClientController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:client-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test toutes les méthodes du ClientController';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new ClientController;

        $this->info('🧪 Test du ClientController...');

        // Test 1: Index
        $this->info('1. Test de index()...');
        try {
            $response = $controller->index();
            $this->info('   ✅ index() fonctionne');
        } catch (\Exception $e) {
            $this->error('   ❌ index() échoue: '.$e->getMessage());
        }

        // Test 2: Show
        $this->info('2. Test de show()...');
        try {
            $client = Client::first();
            if ($client) {
                $response = $controller->show($client);
                $this->info('   ✅ show() fonctionne');
            } else {
                $this->warn('   ⚠️ Aucun client trouvé pour tester show()');
            }
        } catch (\Exception $e) {
            $this->error('   ❌ show() échoue: '.$e->getMessage());
        }

        // Test 3: Create
        $this->info('3. Test de create()...');
        try {
            $response = $controller->create();
            $this->info('   ✅ create() fonctionne');
        } catch (\Exception $e) {
            $this->error('   ❌ create() échoue: '.$e->getMessage());
        }

        // Test 4: Store
        $this->info('4. Test de store()...');
        try {
            $request = new Request;
            $request->merge([
                'nom' => 'Client Test '.time(),
                'adresse' => '123 Test Street',
                'ville' => 'Test City',
                'code_postal' => '12345',
                'pays' => 'Test Country',
                'telephone' => '0123456789',
                'email' => 'test'.time().'@example.com',
            ]);

            $response = $controller->store($request);
            $this->info('   ✅ store() fonctionne');
        } catch (\Exception $e) {
            $this->error('   ❌ store() échoue: '.$e->getMessage());
        }

        // Test 5: Edit
        $this->info('5. Test de edit()...');
        try {
            $client = Client::first();
            if ($client) {
                $response = $controller->edit($client);
                $this->info('   ✅ edit() fonctionne');
            } else {
                $this->warn('   ⚠️ Aucun client trouvé pour tester edit()');
            }
        } catch (\Exception $e) {
            $this->error('   ❌ edit() échoue: '.$e->getMessage());
        }

        $this->info('🎉 Tests terminés !');
    }
}
