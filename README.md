SimplyAdmire.Neos.FormBuilderBundle
===================================

To use this package you have to add the following routes to your Configuration/Routes.yaml above the Neos routes:

-
  name: 'SimplyAdmire.Neos.FormBuilderBundle'
  uriPattern: '<SimplyAdmireNeosFormBuilderBundleSubroutes>'
  subRoutes:
    SimplyAdmireNeosFormBuilderBundleSubroutes:
      package: SimplyAdmire.Neos.FormBuilderBundle
