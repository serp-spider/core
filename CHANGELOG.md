# CHANGELOG

## 0.3.0 (not released)

> 20xx-xx-xx

## 0.2.6

> 2017-12-11

* new features
  * **bc break** added method ``DomNodeInterface::hasAnyClass``
  * added method ``DomNodeList::hasAnyClass``

## 0.2.5

> 2017-12-10

* new features
  * **bc break** added method ``DomNodeInterface::hasClasses``
  * added method ``DomNodeList::hasClasses``
  
  
## 0.2.4

> 2017-11-25

* new features
  * data value can now depend on other results [aa59e55b10c28645decc5312b9c93681f5fe0691](https://github.com/serp-spider/core/commit/aa59e55b10c28645decc5312b9c93681f5fe0691)
  * BaseResult::getData is now able to dump resultSetInterface [d6e1b3627a50a5cce56d5320b56accabd107d851](https://github.com/serp-spider/core/commit/d6e1b3627a50a5cce56d5320b56accabd107d851)

## 0.2.3

> 2017-08-08

* bug fix
  * url query params was generating bad value for null array value [590ee240e9032ec1538fc6ffe5ad394cb9fac8d7](https://github.com/serp-spider/core/commit/590ee240e9032ec1538fc6ffe5ad394cb9fac8d7)


## 0.2.2

> 2017-07-26

* Addition
  * browser class is now able to set default headers for every requests [serp-spider/search-engine-google#73](https://github.com/serp-spider/search-engine-google/issues/73)

## 0.2.1 

> 2017-06-13

* breaking change:
  * method ResultDataInterface::getData() will now return sub results as parsed arrays instead of objects (e047801)

* bug fix
  * getDataValue failed to parse string value with the name of an existing php function (649c214)

## 0.2.0 

> 2017-05-01
    
* New dependency: ``"symfony/css-selector": "^2|^3"``
    
* breaking changes
  * url interface was refactored [#22](https://github.com/serp-spider/core/pull/22)
    * Internal structure is better (no construct in the interface)
    * now ``port`` and ``user:pass auth``  are supported [#18](https://github.com/serp-spider/core/issues/18) 
    * resolve and resolveAsString are now 2 distinct methods. [#19](https://github.com/serp-spider/core/issues/19)
    * resolve does not support string anymore [d56cbc39e710735296bbdd675431f7b3e87f534c](https://github.com/serp-spider/core/commit/d56cbc39e710735296bbdd675431f7b3e87f534c#diff-2bb04ebe8ec8dc8575afdd6a7a0bc0f6L325)
    * new method ``UrlArchiveInterface::getAuthority``
    * url resolution is now compatible with rfc3986
    * query params now accept empty value [7233b7d1b67ed2a061746c210171b121ac931bb9](https://github.com/serp-spider/core/commit/7233b7d1b67ed2a061746c210171b121ac931bb9#diff-ea6d1c5de04976abd5f773367a57da23R79)
    * fix a bug with query params that are number only [#25](https://github.com/serp-spider/core/pull/25) 
    * url parser is now able to parse array values from query string [#23](https://github.com/serp-spider/core/issues/23)
  * cookie expiration time was not on the same standard everywhere 
  
* Additions
  * Css parser was moved from google package to core [2f7d022d6da4905519a02d65c2f262aefc8b6bbf](https://github.com/serp-spider/core/commit/2f7d022d6da4905519a02d65c2f262aefc8b6bbf)
  * ``Dom`` component that offers better parsing of the dom (replacement for the ``googleDom`` class from google package) [view  commits](https://github.com/serp-spider/core/compare/2f7d022d6da4905519a02d65c2f262aefc8b6bbf...22749d020c953e987dedc452566b4973923bf439)
  * ``RequestBuilder`` class that allows to construct PSR7 request from installed packages (``zendframework/zend-diactoros`` or ``guzzlehttp/psr7``) 
  [98ab9f56bcef0ac36bae2b43cd965d14522a3294](https://github.com/serp-spider/core/commit/98ab9f56bcef0ac36bae2b43cd965d14522a3294)
  * Addition of ``BrowserInterface``, ``AbstractBrowser`` and ``Browser`` [#26](https://github.com/serp-spider/core/pull/26)
  * Addition of ``StackingHttpClient``: a http client implementation for unit test purposes [#26](https://github.com/serp-spider/core/pull/26)

------------------
