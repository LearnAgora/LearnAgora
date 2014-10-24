<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 13:59
 */

namespace La\CoreBundle\Visitor;

use La\CoreBundle\Entity\HtmlContent;

interface HtmlContentVisitorInterface
{
    /**
     * @param HtmlContent $content
     **/
    public function visitHtmlContent(HtmlContent $content);
}
