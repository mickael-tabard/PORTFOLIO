<?php

namespace App\Entity;

use App\Repository\TweetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
#[Vich\Uploadable]
class Tweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 280)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'tweets')]
    private ?User $user = null;

    #[Vich\UploadableField(mapping: 'tweets', fileNameProperty: 'media')]
    private ?File $mediaFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $media = null;

    #[ORM\OneToMany(mappedBy: 'tweet', targetEntity: Likes::class)]
    private Collection $likes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function __toString()
    {
        return $this->date;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): static
    {
        $this->media = $media;

        return $this;
    }

    /** 
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $mediaFile 
     * */

    public function setMediaFile(?File $mediaFile = null): void
    {

        $this->mediaFile = $mediaFile;
    }

    public function getMediaFile(): ?File
    {

        return $this->mediaFile;
    }

    public function __construct()
    {
        $this->date = new \DateTimeImmutable();
        $this->likes = new ArrayCollection();
    }

    /**
     * @return Collection<int, Likes>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }
    public function getNumberOfLikes(): int
    {
        return $this->likes->count();
    }

    public function addLike(Likes $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setTweet($this);
        }

        return $this;
    }

    public function removeLike(Likes $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getTweet() === $this) {
                $like->setTweet(null);
            }
        }

        return $this;
    }
}
