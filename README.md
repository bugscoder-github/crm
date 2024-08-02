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
* [ ] Lead
    * [ ]  Beautify audit trail
    * [ ]  Remove activity log from Follow up section (remain inside audit trail only)

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