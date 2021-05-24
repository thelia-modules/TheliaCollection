<?php

namespace TheliaCollection\Model\Api;

use OpenApi\Annotations as OA;
use OpenApi\Model\Api\BaseApiModel;
use OpenApi\Constraint as Constraint;

/**
 * Class TheliaCollection
 * @package OpenApi\Model\Api
 * @OA\Schema(
 *     schema="TheliaCollection",
 *     title="TheliaCollection",
 * )
 */
class TheliaCollection extends BaseApiModel
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
    protected $title;

    /**
     * @var string
     * @OA\Property(
     *     type="string",
     * )
     */
    protected $code;

    /**
     * @var boolean
     * @OA\Property(
     *     type="boolean",
     * )
     */
    protected $visible = true;

    /**
     * @var integer
     * @OA\Property(
     *     type="integer",
     * )
     */
    protected $position;

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
     * @var array
     * @OA\Property(
     *     readOnly=true,
     *     type="array",
     *     @OA\Items(
     *          ref="#/components/schemas/TheliaCollectionItem"
     *     )
     * )
     */
    protected $items = [];

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return TheliaCollection
     */
    public function setId(int $id): TheliaCollection
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return TheliaCollection
     */
    public function setTitle(?string $title): TheliaCollection
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return TheliaCollection
     */
    public function setCode(?string $code): TheliaCollection
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     * @return TheliaCollection
     */
    public function setVisible(?bool $visible = true): TheliaCollection
    {
        $this->visible = $visible;
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
     * @return TheliaCollection
     */
    public function setPosition(?int $position): TheliaCollection
    {
        $this->position = $position;
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
     * @return TheliaCollection
     */
    public function setItemType(string $itemType): TheliaCollection
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
     * @return TheliaCollection
     */
    public function setItemId(int $itemId): TheliaCollection
    {
        $this->itemId = $itemId;
        return $this;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return TheliaCollection
     */
    public function setItems(array $items): TheliaCollection
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @param array $items
     * @return TheliaCollection
     */
    public function setTheliaCollectionItems(array $items): TheliaCollection
    {
        $this->items = $items;
        return $this;
    }

    protected function getTheliaModel($propelModelName = null)
    {
        return parent::getTheliaModel(\TheliaCollection\Model\TheliaCollection::class);
    }
}
