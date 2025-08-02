<?php

use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

describe('Course Purchase', function () {
    it('allows a user to purchase a course with enough sparkies', function () {
        $user = User::factory()->create(['sparkies' => 100]);
        $course = Course::factory()->create(['price' => 50]);
        $this->actingAs($user);
        $response = $this->postJson("/api/course/{$course->id}/purchase");
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        $user->refresh();
        expect($user->sparkies)->toBe(50);
        expect($user->courses()->where('course_id', $course->id)->exists())->toBeTrue();
    });

    it('fails if user does not have enough sparkies', function () {
        $user = User::factory()->create(['sparkies' => 10]);
        $course = Course::factory()->create(['price' => 50]);
        $this->actingAs($user);
        $response = $this->postJson("/api/course/{$course->id}/purchase");
        $response->assertStatus(400)
            ->assertJson(['success' => false, 'message' => 'Insufficient sparkies']);
        $user->refresh();
        expect($user->sparkies)->toBe(10);
        expect($user->courses()->where('course_id', $course->id)->exists())->toBeFalse();
    });

    it('fails if user already purchased the course', function () {
        $user = User::factory()->create(['sparkies' => 100]);
        $course = Course::factory()->create(['price' => 50]);
        $user->courses()->attach($course->id);
        $this->actingAs($user);
        $response = $this->postJson("/api/course/{$course->id}/purchase");
        $response->assertStatus(400)
            ->assertJson(['success' => false, 'message' => 'Already purchased']);
    });

    it('fails if course does not exist', function () {
        $user = User::factory()->create(['sparkies' => 100]);
        $this->actingAs($user);
        $response = $this->postJson("/api/course/999999/purchase");
        $response->assertStatus(404)
            ->assertJson(['success' => false, 'message' => 'Course not found']);
    });
});

describe('Courses Overview', function () {
    it('returns all course categories in a single response', function () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->getJson('/api/courses/overview');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'recommendedCourses',
                'topRatedCourses',
                'recentCourses',
                'allCourses',
            ]);
    });
});
