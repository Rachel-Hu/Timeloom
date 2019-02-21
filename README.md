## My thoughts on the interface of user input(task-list) & server:
- Providing the tasks are included in txt file, let the user upload the file. The server cache the file, parse (separate items from) it, store in the database, and remove the cached file.
    - Possible problems I can think of: security questions and time efficiency. 
    - Improvement: any process could be done in the browser? Ways to avoid security issues?
    - Or, would it be even faster to store the txt file in the database, since the txt file will not be too large?

- The influence of user input format will also matter in practice.
    - Easiest way is through html forms. This will work, but only when the user want to add one or two items.
    - How about csv or excel files?

## Current code and unfinished tasks
- Current code takes the input file, check it from several aspects, and display it directly.
- Unfinished tasks:
    - Connect to the database and store the parsed tasks;
    - User login and register page (in progress);
    - UI improvement (including more appropriate error display).
- One issue of current mechanism: I'll have to give Apache permissions to handle the uploaded files. I'm thinking of a possibly better way.


