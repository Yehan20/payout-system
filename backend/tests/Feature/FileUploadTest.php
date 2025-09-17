<?php

namespace Tests\Feature;

use App\Jobs\ProcessPaymentFileJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
        $this->user = $this->createUser();
    }

    public function test_upload_csv_file_successfully()
    {

        Bus::fake();

        $file = UploadedFile::fake()->create('test.csv', 1000, 'text/csv');

        $response = $this->actingAs($this->user)->postJson('api/upload', ['file' => $file]);

        Bus::assertDispatched(ProcessPaymentFileJob::class);

        $response->assertNoContent();
    }

    public function test_upload_csv_file_type_is_invalid_validation_error()
    {

        $response = $this->actingAs($this->user)->postJson('/api/upload', [
            'file' => UploadedFile::fake()->create('document.pdf', 100), // invalid type
        ]);

        $response->assertStatus(422); // assuming your CsvRequest validates mime types
        $response->assertJsonValidationErrors('file');
    }

    private function createUser(): User
    {
        return User::factory()->create();
    }
}
