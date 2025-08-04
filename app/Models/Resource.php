<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceRating;
use Illuminate\Support\Facades\Auth;
use getID3;

class Resource extends Model
{

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    /** @use HasFactory<\Database\Factories\ResourceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'literaryOrScientific',
        'subject_id',
        'publish date',
        'image',
        'audio_file',
        'pdf_file',
        'author',
        'created_at',
        'updated_at'
    ];

    public function ratings()
    {
        return $this->hasMany(ResourceRating::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getRatingAttribute()
    {
        $avgRating = $this->ratings()->avg('rating');
        return $avgRating ? round($avgRating, 2) : null;
    }

    public function getSubjectNameAttribute()
    {
        return $this->subject->name;
    }


    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
    }

    public function getRatingBreakdownAttribute()
    {

        $breakdown = $this->ratings()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();


        $fullBreakdown = [];
        foreach (range(1, 5) as $rating) {
            $fullBreakdown[$rating] = isset($breakdown[$rating]) ? $breakdown[$rating] : 0;
        }

        return $fullBreakdown;
    }

    public function getUserRatingAttribute()
    {
        if (!Auth::check()) {
            return null;
        }

        $rating = Auth::user()->resourceRatings()->where('resource_id', $this->id)->first();
        return $rating ? $rating->rating : null;
    }

    public function getFeaturedRatingsAttribute()
    {
        $withReview = $this->ratings()
            ->with('user')
            ->whereNotNull('review')
            ->orderByDesc('rating')
            ->orderByRaw('LENGTH(review) DESC')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        if ($withReview->count() >= 3) {
            return $withReview->map(function($review) {
                $review->user_name = $review->user ? $review->user->userName : null;
                return $review;
            });
        }

        $needed = 3 - $withReview->count();
        $withoutReview = $this->ratings()
            ->with('user')
            ->whereNull('review')
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->take($needed)
            ->get();

        $all = $withReview->concat($withoutReview);
        return $all->map(function($review) {
            $review->user_name = $review->user ? $review->user->userName : null;
            return $review;
        });
    }

    public function getPDFFileUrlAttribute() {
        $value = $this->attributes['pdf_file'] ?? null;
        return ($value ? url($value) : null);
    }

    public function getAudioFileUrlAttribute() {
        $value = $this->attributes['audio_file'] ?? null;
        return ($value ? url($value) : null);
    }

    public function getAudioFileDurationSecondsAttribute() {
        $value = $this->attributes['audio_file'] ?? null;
        if (!$value) return null;
        $filePath = public_path($value);
        if (!file_exists($filePath)) return null;
        try {
            $getID3 = new getID3();
            $info = $getID3->analyze($filePath);
            if (isset($info['playtime_seconds'])) {
                return (float) $info['playtime_seconds'];
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }

    public function getAudioFileDurationFormattedAttribute() {
        $seconds = $this->audio_file_duration_seconds;
        if ($seconds === null) return null;
        $minutes = floor($seconds / 60);
        $secs = round($seconds % 60);
        return sprintf('%02d:%02d', $minutes, $secs);
    }

    public function getAudioFileDurationLongFormattedAttribute() {
        $seconds = $this->audio_file_duration_seconds;
        if ($seconds === null) return null;
        $hours = round($seconds / 3600);
        $minutes = floor($seconds / 60);
        $secs = round($seconds % 60);
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }

    public function getAudioFileDurationHumanAttribute() {
        $seconds = $this->audio_file_duration_seconds;
        if ($seconds === null) return null;
        $minutes = floor($seconds / 60);
        $secs = round($seconds % 60);
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        if ($hours > 0) {
            return $hours . ' hour' . ($hours > 1 ? 's ' : ' ') . $mins . ' min ' . $secs . ' sec';
        } elseif ($minutes > 0) {
            return $minutes . ' min ' . $secs . ' sec';
        } else {
            return $secs . ' sec';
        }
    }

    public function getPdfFilePagesAttribute() {
        $value = $this->attributes['pdf_file'] ?? null;
        if (!$value) return null;
        $filePath = public_path($value);
        if (!file_exists($filePath)) return null;
        try {
            // Use getID3 if available
            $getID3 = new getID3();
            $info = $getID3->analyze($filePath);
            if (isset($info['pdf']['pages'])) {
                return (int) $info['pdf']['pages'];
            }
            // Fallback: try to count /Type /Page in the file (very basic)
            $content = @file_get_contents($filePath);
            if ($content) {
                preg_match_all("/\/Type\s*\/Page[^s]/", $content, $matches);
                if (isset($matches[0])) {
                    return count($matches[0]);
                }
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }

    protected $appends = [
        'subjectName',
        'rating',
        'rating_breakdown',
        'FeaturedRatings',
        'pdf_file_url',
        'audio_file_url',
        'audio_file_duration_seconds',
        'audio_file_duration_formatted',
        'audio_file_duration_long_formatted',
        'audio_file_duration_human',
        'pdf_file_pages',
    ];
}
