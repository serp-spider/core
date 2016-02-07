SERPS
=====

**S**earch **E**ngine **R**esult **P**age **S**crapper

[![Build Status](https://travis-ci.org/serp-scrape/serps.svg?branch=master)](https://travis-ci.org/serp-scrape/serps)
[![Test Coverage](https://codeclimate.com/github/serp-scrape/serps/badges/coverage.svg)](https://codeclimate.com/github/serp-scrape/serps/coverage)


This is a [work in progress](STATUS.md).



Install
-------

Install it using composer: ``composer require serps/serps``

The base package does not include an http client. 
We advice you to use our curl client: ``composer require serps/http-client-curl``

Usage
-----

The following usage is only a short example, you can check the full documentation at (TODO)


```php

    use Serps\SearchEngine\Google\GoogleClient;
    use Serps\HttpClient\CurlClient;
    use Serps\SearchEngine\Google\GoogleUrl;
    use Serps\Exception\CaptchaException;
    use Serps\Exception\RequestErrorException;
    
    $googleClient = new GoogleClient(new CurlClient());
    
    $googleUrl = new GoogleUrl('google.fr');
    $googleUrl->setSearchTerm('simpsons');
    
    try {
        $googleSerp = $googleClient->query($googleUrl);
        $results = $googleSerp->getNaturalResults();
        
        foreach($results as $result){
            $resultType = $result->getType();
            $resultPosition = $result->getPosition();
            // an array of data depending on the result type
            $resultData = $result->getData(); 
        } 
    } catch (CaptchaException $e) {
        // Captcha must be solved to continue with this ip
    } catch (RequestErrorException $e) {
        // Non-captcha error (not found, network error...)
    }

```
