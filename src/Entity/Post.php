<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ApiProperty(
        identifier: true,
        description: 'Unique identifier for the product.',
        openapiContext: [
            'type' => 'integer',
            'example' => 1,
        ]
    )]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; // @phpstan-ignore-line

    #[ApiProperty(
        description: 'Title of the post',
        openapiContext: [
            'type' => 'string',
            'example' => 'My first post',
        ]
    )]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $title = null;

    #[ApiProperty(
        description: 'Text of the post',
        openapiContext: [
            'type' => 'string',
            'example' => 'This is the content of my first post.',
        ]
    )]
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 5000)]
    private ?string $text = null;

    #[ApiProperty(
        description: 'Creation date of the post',
        openapiContext: [
            'type' => 'string',
            'example' => '2024-12-31T23:59:59+00:00',
        ]
    )]
    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\DateTime]
    #[Assert\GreaterThanOrEqual('today')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ApiProperty(
        description: 'Scheduled publication date of the post',
        openapiContext: [
            'type' => 'string',
            'example' => '2024-12-31T23:59:59+00:00',
        ]
    )]
    #[ORM\Column(nullable: true)]
    #[Assert\DateTime]
    #[Assert\GreaterThan('today')]
    private ?\DateTimeImmutable $scheduledAt = null;

    #[ApiProperty(
        description: 'Author of the post',
        openapiContext: [
            'type' => 'string',
            'example' => '1',
        ]
    )]
    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ApiProperty(
        description: 'Tags associated with the post',
        openapiContext: [
            'type' => 'array',
            'example' => [],
        ]
    )]
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'posts')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getScheduledAt(): ?\DateTimeImmutable
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(?\DateTimeImmutable $scheduledAt): static
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
