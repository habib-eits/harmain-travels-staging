<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pnr;
use App\Models\User;
use App\Models\Branch;

class PnrTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->user = User::factory()->create();
        $this->branch = Branch::factory()->create();
    }

    public function test_pnr_index_page_can_be_rendered()
    {
        $response = $this->get(route('pnr.index'));
        $response->assertStatus(200);
    }

    public function test_pnr_create_page_can_be_rendered()
    {
        $response = $this->get(route('pnr.create'));
        $response->assertStatus(200);
    }

    public function test_pnr_can_be_created()
    {
        $pnrData = [
            'branch_id' => $this->branch->id,
            'UserID' => $this->user->id,
            'pnr' => 'ABC123',
            'FlightNoDeparture' => 'PK123',
            'SectorDeparture' => 'KHI-LHR',
            'FlightDateDeparture' => '2024-12-25',
            'FlightTimeDeparture' => '10:30',
        ];

        $response = $this->post(route('pnr.store'), $pnrData);
        
        $this->assertDatabaseHas('pnr', [
            'pnr' => 'ABC123',
            'FlightNoDeparture' => 'PK123',
        ]);
    }

    public function test_pnr_can_be_updated()
    {
        $pnr = Pnr::factory()->create([
            'branch_id' => $this->branch->id,
            'UserID' => $this->user->id,
        ]);

        $updateData = [
            'branch_id' => $this->branch->id,
            'UserID' => $this->user->id,
            'pnr' => 'UPDATED123',
            'FlightNoDeparture' => 'PK456',
        ];

        $response = $this->put(route('pnr.update', $pnr->id), $updateData);
        
        $this->assertDatabaseHas('pnr', [
            'id' => $pnr->id,
            'pnr' => 'UPDATED123',
            'FlightNoDeparture' => 'PK456',
        ]);
    }

    public function test_pnr_can_be_deleted()
    {
        $pnr = Pnr::factory()->create([
            'branch_id' => $this->branch->id,
            'UserID' => $this->user->id,
        ]);

        $response = $this->delete(route('pnr.destroy', $pnr->id));
        
        $this->assertDatabaseMissing('pnr', [
            'id' => $pnr->id,
        ]);
    }

    public function test_pnr_show_page_can_be_rendered()
    {
        $pnr = Pnr::factory()->create([
            'branch_id' => $this->branch->id,
            'UserID' => $this->user->id,
        ]);

        $response = $this->get(route('pnr.show', $pnr->id));
        $response->assertStatus(200);
    }

    public function test_pnr_edit_page_can_be_rendered()
    {
        $pnr = Pnr::factory()->create([
            'branch_id' => $this->branch->id,
            'UserID' => $this->user->id,
        ]);

        $response = $this->get(route('pnr.edit', $pnr->id));
        $response->assertStatus(200);
    }
}

