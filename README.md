**10 Aug, 2024**
* Quotation Revamp
* Product Structure
* Added Languages Support


**02 Aug, 2024**

NEW:
* Quotation
    * Create quotation from lead
    * Generate PDF
* ```Lead``` Audit Trail
* ```Lead``` reopen closed lead

TODO:
* [ ] Quotation
    * [ ] if quotation created from lead, display lead info on the quotation form as reference
    * [ ] quotation audit trail
    * [ ] default sst value
    * [ ] quotation number suffix and prefix
    * [ ] choose item to be transfered to invoice
* [ ] Lead
    * [ ]  Beautify audit trail
    * [ ]  Remove activity log from Follow up section (remain inside audit trail only)

NEXT ACTION:
* [ ]  Invoice
    * [ ]  Salesperson to generate invoice
    * [ ]  Admin need to approve for invoice to be generated
    * [ ]  status
        * [ ]  service provided
        * [ ]  paid
* [ ]  Admin able to set default value

---

**24 Jul, 2024** (via whatsapp)

TODO:
* [ ] message from whatsapp
* [ ] new enquiry alert (without refreshing page)
* [ ] lead form make one button only
* [ ] channge follow up submit button to dropdown
    * Status
    * Pending
    * Called & Whatsapp
    * Quotation Sent
    * Price Given
    * Site Visit
    * Sales Closed
    * Dropped

---

**11 Jul, 2024**

NEW:
* Lead Form
* Contact us Form (public)
* Customer database
* User database

TODO:
* [ ] User
    * [ ] Branch
    * [ ] Reassigned Sales Person
* [ ] Lead
    * [X] ~~*Audit Trail*~~ [2024-08-02]
    * [X] ~~*Reopen Done Status*~~ [2024-08-02]
    * [ ] ```feature``` user able to accept/reject job
    * [ ] ```feature``` user able to pick up from unassigned pool
    * [ ] ```ui``` hide done
    * [ ] ```ui``` change dropdown to select2

---

### Tech Notes

1. Generate migrate file
    ```
    php artisan migrate:generate
    ```
2. Drop and migrate database
   ```
   php artisan migrate:fresh --seed
   ```
