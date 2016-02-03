CONTRIBUTING
============

Any contribution is welcome.

Issues
------

- Your issue is related to a SERP parsing? 

When you report the issue try to include as much details as possible about your current search.
SERPs are dependant on many factors and we need to know all of them.

Tests
-----

All contributions must be tested following as much as possible the current test structure:

Look at current tests in ``test/suites`` for more details. Think about adding ``@cover`` annotation. 

If your test fixes an issue, first you will have to reproduce this issue in the test suit and you can comment 
your test to tell it fixes the given issue.

Coding standards
----------------



- Check standards: run ``./test/bin/phpcs.bash emacs``
- fix standards: run ``./test/bin/phpcbf.bash``
