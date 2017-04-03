<?php
namespace SimplyAdmire\Neos\FormBuilderBundle\DataSource;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\ContentRepository\Domain\Model\NodeInterface;

class FormsDataSource extends AbstractDataSource
{

    /**
     * @Flow\Inject
     * @var \Neos\Form\Persistence\FormPersistenceManagerInterface
     */
    protected $formPersistenceManager;

    /**
     * @var string
     */
    protected static $identifier = 'simplyadmire-neos-formbuilderbundle';

    /**
     * Get data
     *
     * @param NodeInterface $node The node that is currently edited (optional)
     * @param array $arguments Additional arguments (key / value)
     * @return array JSON serializable data
     */
    public function getData(NodeInterface $node = null, array $arguments)
    {
        $forms = $this->formPersistenceManager->listForms();
        $formIdentifiers = [];

        foreach ($forms as $key => $value) {
            $formIdentifiers[$key] = [
                'label' => $value['name'],
                'value' => $key
            ];
        }

        return $formIdentifiers;
    }
}
