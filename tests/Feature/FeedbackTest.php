<?php

namespace Tests\Feature;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_dapat_membalas_feedback_dan_memperbarui_status(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
        ]);

        $feedback = Feedback::create([
            'user_id' => $mahasiswa->id,
            'jenis' => 'tanya_pustakawan',
            'isi' => 'Apakah buku bisa dipinjam lebih lama?',
            'status' => 'baru',
        ]);

        $response = $this->actingAs($petugas)
            ->post(route('petugas.feedback.balas', $feedback), [
                'balasan' => 'Bisa, masa pinjam diperpanjang 3 hari.',
            ]);

        $response->assertSessionHas('success', 'Balasan terkirim.');
        $this->assertDatabaseHas('feedback', [
            'id' => $feedback->id,
            'balasan' => 'Bisa, masa pinjam diperpanjang 3 hari.',
            'status' => 'selesai',
        ]);
    }
}
