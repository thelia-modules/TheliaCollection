<?php

namespace TheliaCollection\Model\Api;

use OpenApi\Annotations as OA;
use OpenApi\Model\Api\BaseApiModel;
use OpenApi\Constraint as Constraint;

/**
 * Class TheliaCollectionItem
 * @package OpenApi\Model\Api
 * @OA\Schema(
 *     schema="TheliaCollectionItem",
 *     title="TheliaCollectionItem",
 * )
 */
class TheliaCollectionItem extends BaseApiModel
{
    /**
     * @var integer
     * @OA\Property(
     *    type="integer",
     * )
     * @Constraint\NotBlank(groups={"read"})
     */
    protected $id;

    /**
     * @var string
     * @OA\Property(
     *     type="string",
     * )
     */
    protected $itemType;

    /**
     * @var integer
     * @OA\Property(
     *     type="integer",
     * )
     */
    protected $itemId;

    /**
     * @var integer
     * @OA\Property(
     *     type="integer",
     * )
     */
    protected $position;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return TheliaCollectionItem
     */
    public function setId(int $id): TheliaCollectionItem
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemType(): string
    {
        return $this->itemType;
    }

    /**
     * @param string $itemType
     * @return TheliaCollectionItem
     */
    public function setItemType(string $itemType): TheliaCollectionItem
    {
        $this->itemType = $itemType;
        return $this;
    }

    /**
     * @return int
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }

    /**
     * @param int $itemId
     * @return TheliaCollectionItem
     */
    public function setItemId(int $itemId): TheliaCollectionItem
    {
        $this->itemId = $itemId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return TheliaCollectionItem
     */
    public function setPosition(?int $position): TheliaCollectionItem
    {
        $this->position = $position;
        return $this;
    }

    protected function getTheliaModel($propelModelName = null)
    {
        return parent::getTheliaModel(\TheliaCollection\Model\TheliaCollectionItem::class);
    }
}
