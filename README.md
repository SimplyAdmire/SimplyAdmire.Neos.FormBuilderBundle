SimplyAdmire.Neos.FormBuilderBundle
===================================

To use this package you have to add the following routes to your Configuration/Routes.yaml above the Neos routes:

```
-
  name: 'SimplyAdmire.Neos.FormBuilderBundle'
  uriPattern: '<SimplyAdmireNeosFormBuilderBundleSubroutes>'
  subRoutes:
    SimplyAdmireNeosFormBuilderBundleSubroutes:
      package: SimplyAdmire.Neos.FormBuilderBundle
```

note: if you use `dev-master` of the formbuilder bundle in a Neos 1.0, 1.1 or 1.2 project you need to switch to 0.1.0 (for Neos 1.0 and 1.1) or 0.1.1 (for Neos 1.2)
