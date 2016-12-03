<?php
namespace SimplyAdmire\Neos\FormBuilderBundle\Finishers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Form".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Routing\UriBuilder;
use TYPO3\Flow\Utility\Arrays;
use TYPO3\Neos\Domain\Repository\DomainRepository;
use TYPO3\Neos\Domain\Repository\SiteRepository;
use TYPO3\Neos\Domain\Service\ContentContext;
use TYPO3\Neos\Domain\Service\ContentContextFactory;
use TYPO3\Neos\Service\LinkingService;

/**
 * This finisher redirects to a specified nodePath.
 * @author support@simplyadmire.com
 */
class NodeRedirectFinisher extends \TYPO3\Form\Core\Model\AbstractFinisher
{

    /**
     * @var array
     */
    protected $defaultOptions = [
        'nodePath' => NULL,
        'delay' => 0,
        'statusCode' => 303
    ];

    /**
     * @Flow\Inject
     * @var LinkingService
     */
    protected $linkingService;

    /**
     * @var ContentContext
     */
    protected $contentContext;

    /**
     * @Flow\Inject
     * @var ContentContextFactory
     */
    protected $contentContextFactory;

    /**
     * @Flow\Inject
     * @var DomainRepository
     */
    protected $domainRepository;

    /**
     * @Flow\Inject
     * @var SiteRepository
     */
    protected $siteRepository;

    /**
     * Executes this finisher
     * @see AbstractFinisher::execute()
     *
     * @return void
     * @throws \TYPO3\Form\Exception\FinisherException
     */
    protected function executeInternal()
    {

        /** @var array $contextParameters */
        $contextParameters = !empty($this->parseOption('dimensions')) ? ['dimensions' => $this->parseOption('dimensions')] : [];

        /** @var \TYPO3\Neos\Domain\Service\ContentContext $contentContext */
        $contentContext = $this->getContentContext($contextParameters);
        $node = $contentContext->getNode($this->parseOption('nodePath'));

        $uriBuilder = new UriBuilder();
        $uriBuilder->setRequest($this->finisherContext->getFormRuntime()->getRequest()->getMainRequest());
        $uri = $uriBuilder
            ->reset()
            ->setCreateAbsoluteUri(TRUE)
            ->uriFor('show', ['node' => $node], 'Frontend\Node', 'TYPO3.Neos');

        $delay = (integer)$this->parseOption('delay');
        $statusCode = $this->parseOption('statusCode');

        $escapedUri = htmlentities($uri, ENT_QUOTES, 'utf-8');

        $response = $this->finisherContext->getFormRuntime()->getResponse();
        $mainResponse = $response;
        $mainResponse->setContent('<html><head><meta http-equiv="refresh" content="' . $delay . ';url=' . $escapedUri . '"/></head></html>');
        $mainResponse->setStatus($statusCode);
        if ($delay === 0) {
            $mainResponse->setHeader('Location', (string)$uri);
        }

        $mainResponse->send();
    }

    /**
     * @param array $contextProperties
     * @return ContentContext
     */
    public function getContentContext(array $contextProperties = [])
    {
        if ($this->contentContext instanceof ContentContext) {
            return $this->contentContext;
        }

        $contextPropertiesArray = ['workspaceName' => 'live'];
        $contextProperties = Arrays::arrayMergeRecursiveOverrule($contextPropertiesArray, $contextProperties);

        $currentDomain = $this->domainRepository->findOneByActiveRequest();

        if ($currentDomain !== NULL) {
            $contextProperties['currentSite'] = $currentDomain->getSite();
            $contextProperties['currentDomain'] = $currentDomain;
        } else {
            $contextProperties['currentSite'] = $this->siteRepository->findFirstOnline();
        }

        $this->contentContext = $this->contentContextFactory->create($contextProperties);
        return $this->contentContext;
    }
}
