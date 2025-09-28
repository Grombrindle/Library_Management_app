<?php

namespace App\Services\Resources;

use App\Actions\Resources\FetchResourceAction;
use App\Actions\Resources\FetchFromSubjectAction;
use App\Actions\Resources\FetchAllResourcesAction;
use App\Actions\Resources\FetchAllRecentResourcesAction;
use App\Actions\Resources\FetchAllRatedResourcesAction;
use App\Actions\Resources\FetchAllRecommendedResourcesAction;
use App\Actions\Resources\FetchAllPageResourcesAction;
use App\Actions\Resources\FetchResourceRatingsAction;
use App\Actions\Resources\RateResourceAction;
use App\Actions\Resources\AddResourceAction;
use App\Actions\Resources\EditResourceAction;
use App\Actions\Resources\DeleteResourceAction;

class ResourceService
{
    public function __construct(
        protected FetchResourceAction $fetchResource,
        protected FetchFromSubjectAction $fetchFromSubject,
        protected FetchAllResourcesAction $fetchAll,
        protected FetchAllRecentResourcesAction $fetchAllRecent,
        protected FetchAllRatedResourcesAction $fetchAllRated,
        protected FetchAllRecommendedResourcesAction $fetchAllRecommended,
        protected FetchAllPageResourcesAction $fetchAllPage,
        protected FetchResourceRatingsAction $fetchRatings,
        protected RateResourceAction $rateResource,
        protected AddResourceAction $addResource,
        protected EditResourceAction $editResource,
        protected DeleteResourceAction $deleteResource,
    ) {}

    /* Fetch single resource (nullable id handled in action) */
    public function getResource(?int $id)
    {
        return $this->fetchResource->execute($id);
    }

    /* Fetch resources for a subject */
    public function getFromSubject(?int $subjectId)
    {
        return $this->fetchFromSubject->execute($subjectId);
    }

    /* Fetch all resources */
    public function getAll()
    {
        return $this->fetchAll->execute();
    }

    /* Fetch recent resources */
    public function getAllRecent()
    {
        return $this->fetchAllRecent->execute();
    }

    /* Fetch rated resources */
    public function getAllRated()
    {
        return $this->fetchAllRated->execute();
    }

    /* Fetch recommended resources (uses auth where available) */
    public function getAllRecommended()
    {
        return $this->fetchAllRecommended->execute();
    }

    /* Fetch page payload (cached) */
    public function getAllPage()
    {
        return $this->fetchAllPage->execute();
    }

    /* Fetch ratings for a resource */
    public function getRatings(int $resourceId): array
    {
        return $this->fetchRatings->execute($resourceId);
    }

    /* Create/update rating for a resource */
    public function rate(int $resourceId, int $rating, ?string $review = null): array
    {
        return $this->rateResource->execute([
            'resource_id' => $resourceId,
            'rating' => $rating,
            'review' => $review,
        ]);
    }

    /* Add a resource (expects action to handle persistence/files if implemented) */
    public function add(array $data): array
    {
        return $this->addResource->execute($data);
    }

    /* Edit a resource */
    public function edit(int $id, array $data): array
    {
        return $this->editResource->execute($id, $data);
    }

    /* Delete a resource */
    public function delete(int $id): array
    {
        return $this->deleteResource->execute($id);
    }
}