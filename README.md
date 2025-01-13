# Senate PHP Laravel Assessment


## Assignment
Create an api endpoint that sends an email based on a post containing (recipient id, sender last name, sender's address, message). 

Validate post data, check entity exists in db

Return status codes based on:validation, user found and email send status


## Assumptions
Wording is unclear in assignment in the following ways, taken interpretations:

'being posted to an API containing' - I have taken this to mean that we are posting data listed to the end point we are creating

'containing' - I am also taking this to mean we want a db record (rather than like a temp table or something), creating a table and seeder quick to make it easy

'that sends an email' - this is an implementation detail more than an assumption, 
I grabbed a trial of a sass platform called mailersend which has a free tier smtp relay. 
I imagine whatever we would be using would be a swap in replacement or completely opaque to the task of sending an email

'log the failures' - writing to a local log file since no logging platform was requested

'handling security issues' - 
1. apache or nginx config - assumed out of scope for this assessment
2. csp / .htaccess - no frontend components for this domain technically so we could lock it down all the way, but no details provided about this so leaving blank as part of the environment hardening
3. rate limiting - not specified but adding for now since this is configurable and an SLA is not given 
4. queueing / storing / exploits - in the case that the msg contains toxic content we don't really want to store this. 
However if we have audit policies that require storage, we should probably store in a fully escaped / render safe method (tokenize html, script tags and escape sequences into an encoded format that does not harm a render context)
I am adding some pretty simple data checking, but depending on the level of sensitivity we could be looking for 
      1. link checkers - malware links, malformed links, links containing unicode chars 
      2. tracking tag removals
      3. image blocking 
      4. content filtering - offensive speech if that is a provided service. AI Text detection if needed 
      5. phishing content detection
5. Route groups - I added a signature check middleware applied to the api end point to prevent it from being accessed without a valid signature generated with the api key and secret. Also
 added a dev route protection for the test form, locking it down to local (since we don't have a frontend that talks to this)
6. api protection - it was not specified whether this was a public method or a private one. 
 Generally speaking we should not be exposing an endpoint to the public that has side effects or the potential to increase spam
 But having a way to message a senator should be a limited, identifiable procedure, protected by methods that fit the use. 
    1. if we are concerned about spam, we could import spam control tools, or write up similarity scoring tools to rank whether a message is too similar to ones sent previously
    2. rate limiting
    3. jwt - if we are sending this from another service we can require the auth context be passed along, and preserve the record of the user 'send' 
    4. For today I have settled on a public key encryption signature to basically be sure whatever is sending to this endpoint is allowed to, if this was public we would not be using this, however the nature of the endpoint was not specified  
    5. Message size limitations - I added some sane limits for messages, if we had requirements for this system we could determine the field lengths and match them to a storage medium and whether we want to add additional denial of service protections
8. PII - logging systems should not contain PII if possible, depending on the level of bug reporting / error recovery required which makes logging a failure containing PII either 
a masking exercise or a policy matter relating to what we are allowed to store from a given type of request

Testing - no tests were requested, so my test harness will suffice most purposes, given that 90% of all of the code was actually just stringing together laravel utils. 
The reusables that I wrote up probably could get tests given that it would increase coverage for every new feature that uses them 
(Middleware checks, ResponseFormatter, Email Service (though even that doesn't have unique code so far))
I did however write up a couple of small tests for the Request handler, but it does require the db to be working and seeded for it's cases to assert anything given that one of the rules is that the senator id must exist

Logging:
I left off the form data because we do not have a spec on what the acceptable PII data would be that can be logged. I do not want a failed audit and I do not want an attack against a log parser. 
If I get a requirement I am happy to log beyond 'something failed' and here was the field that failed




# Setup
I used laravel sail (docker to set this up because that is what I had laying around on my home machine)
An example env file with my mailtrap and current db config are setting in the setup folder. 
Other than a clean redis, db and mailtrap config you just need to make sure the app_env is set to local if you want to 
use the test harness and the API key and secret should be set (I just used the laravel encryption secret as the secret and hard coded an API key in the env)

Otherwise hosting this thing on a local machine should look like most sites:
1. copy setup/.env.example to .env
1. vhost pointing to public
2. mysql config in place and able to read / write
3. redis config (or switch the cache / session drivers to file)
4. mailtrap able to send out over the smtp port set in the env
5. This is a PHP 8.2 / Laravel 11 app (its just what I grabbed, all the code would work under laravel 12 as well)
