<?php

namespace AppBundle\Services;

use AppBundle\Entity\Topic;
use Doctrine\ORM\EntityManager;
use AppBundle\Interfaces\Services\CrudInterface;

/**
 * TopicService
 *
 * @author Daniel
 */
class TopicService implements CrudInterface {

    const ENTITY_NAME = 'AppBundle:Topic';

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * insert Topic into table topics
     * @param Topic $topic
     */
    public function create($topic) {
        $this->em->persist($topic);
        $this->em->flush($topic);
    }

    /**
     * Update Topic from table topics
     * @param Topic $topic
     */
    public function update($topic) {
        $this->em->flush($topic);
    }

    /**
     * Delete topic from table topic
     * @param Topic $topic
     */
    public function delete($topic) {

        $this->em->remove($topic);
        $this->em->flush();
    }

    /**
     * Get topic by id
     * @param integer $id
     */
    public function findById($id) {
        return $this->em->getRepository(self::ENTITY_NAME)->find($id);
    }

    /**
     * Get all topics or filtered by topicName
     * @param string $topicName
     * @return array AppBundle\Entity\Topic
     */
    public function getTopics($topicName) {

        if (!empty($topicName)) {
            $topics = $this->findLikeName($topicName);
        } else {
            $topics = $this->findAllOrderedByName();
        }

        return $topics;
    }

    /**
     * Get topics filtering by topicName
     * @param string $topicName
     * @return array Topic
     */
    public function findLikeName($topicName) {
        return $this->em->getRepository(self::ENTITY_NAME)->findLikeName($topicName);
    }

    /**
     * Get All topics ordered DESC by Id
     * @return array Topic
     */
    public function findAllOrderedByName() {
        return $this->em->getRepository(self::ENTITY_NAME)->findAllOrderedByName();
    }

}
