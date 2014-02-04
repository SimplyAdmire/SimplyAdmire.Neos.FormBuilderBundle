NodeRedirectFinisher
====================

Important notes
---------------

Currently the finisher is only compatible when including a form using a Neos plugin
due to the fact we are in a sub-process then.

Usage
-----

In the Settings.yaml from your site package include the reference to the redirect
class:

.. code::

    TYPO3:
      Form:
       presets:
         default: # fill in your preset name here, or "default"
           finisherPresets:
             'SimplyAdmire:Redirect':
                implementationClassName: 'SimplyAdmire\Neos\FormBuilderBundle\Finishers\NodeRedirectFinisher'


In your form use it as any other finisher. The options you have are:

- nodePath: the absolute nodePath of the redirect page
- delay: default 0
- statusCode: default 303