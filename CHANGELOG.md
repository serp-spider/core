# CHANGELOG

## 0.2.0 

> Date: *20xx-xx-xx* (unreleased)
    
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
  
  * fix a bug concerning parsing of cookie expiration time [#13](https://github.com/serp-spider/core/pull/13)

------------------
