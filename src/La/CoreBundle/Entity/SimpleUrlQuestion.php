<?php

namespace La\CoreBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\SimpleQuestionVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class SimpleUrlQuestion extends QuestionContent
{
    /**
     * @var string
     *
     * @Serializer\Expose
     */
    private $url;

    public function init($em = null) {
        $answer1 = new Answer();
        $answer2 = new Answer();
        $answer1->setQuestion($this);
        $answer2->setQuestion($this);
        $em->persist($this);
        $em->persist($answer1);
        $em->persist($answer2);
        $em->flush();
    }

    /**
     * Set url
     *
     * @param string $url
     * @return UrlContent
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function accept(VisitorInterface $visitor) {
        if ($visitor instanceof SimpleQuestionVisitorInterface) {
            return $visitor->visitSimpleUrlQuestion($this);
        }

        return null;
    }
}
