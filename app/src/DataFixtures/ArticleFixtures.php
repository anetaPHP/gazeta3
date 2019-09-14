<?php
/**
 * Article fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ArticleFixtures.
 */
class ArticleFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'article', function ($i) {
            $article = new article();
            $article->setTitle($this->faker->sentence);
            $article->setSubtitle($this->faker->word);
            $article->setContent($this->faker->text);
            $article->setCreatedAt($this->faker->dateTime());
            $article->setSlug($this->faker->text);
            $article->setUser($this->getRandomReference('admins'));
            $article->setCategory($this->getRandomReference('category'));

            return $article;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}
