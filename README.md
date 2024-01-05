# RubioDMARC
Web application that allows to check the activity of a Mail Server (MTS)  that runs OpenDMARC as a security check

You will find more information in [OpenDMARC](https://github.com/trusteddomainproject/OpenDMARC/blob/master/opendmarc/README)

## Requirements
- Mail Server (a compatible MTS like Postfix)
- [OpenDMARC](https://github.com/trusteddomainproject/OpenDMARC/blob/master/opendmarc/README), with the reports enabled.
- Database Server (mariaDB or MySQL) wit
- Apache Web Server or Nginx Web server

## OpenDMARC Database fields
Some wording is needed to better understand how OpenDMARC stores the information into the database.
This allows to analyze and display the required reports.

- adkim, aspf:
Published policy's alignment rule for DKIM and SPF (114 = relaxed, 115 = strict)

- align_dkim, align_spf:
Whether identifier alignment was established   for DKIM and SPF (4 = yes, 5 = no)

- spf:
SPF evaluation (0 = pass, 2 = fail, 6 = none, -1 = not evaluated)

- dkim:
DKIM evaluation (signing domain, selector, evaluation - same as SPF)

- pdomain:
Policy domain (the "organizational" domain, the one asserting policy)

- from:
Domain found in the From field

- mfrom:
Domain found in the MAIL FROM parameter

- policy:
Policy to enforce, as follows:
			14 = unknown (no record found)
			15 = pass
			16 = reject
			17 = quarantine
			18 = none

- arc:
ARC evaluation (0 = pass, 2 = fail)

- arc_policy:
ARC local policy evaluation (evaluation -- same as ARC, ARC seal data - JSON-encoded array of governing arc seal fields: instance, domain, selector)

