<?php
/**
 * Tag fixture.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'tag', function ($i) {
            $tag = new Tag();
            $tag->setName($this->faker->word);

            return $tag;
        });

        $manager->flush();
    }
}
