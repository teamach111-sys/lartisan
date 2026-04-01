# TRAINING KIT 7: THE 100-QUESTION GIT MASTERCLASS EXAM

You asked for the maximum questions possible specifically for Git. Since your application involves deploying to production (likely passing through GitHub or GitLab), knowing Git inside and out is non-negotiable. 

This 100-question gauntlet will take you from the absolute basics to hardcore server-level version control commands. 

*Answers are at the extreme bottom.*

---

## Phase 1: Setup, Staging, & The Basics
1. To transform a regular computer folder into a Git repository, you run the command `git ________`.
2. This creates a hidden folder that tracks every single change to your files named `.________`.
3. To tell Git your name so you can get credit for your code, you run `git config --global ________.name "Your Name"`.
4. If you have files like `.env` that contain database passwords, you list them inside a file named `.________` so Git never tracks them.
5. To see which files you have changed, added, or deleted since your last save, you type `git ________`.
6. Modified files exist in your "Working Directory". To prepare them to be saved, you must move them to the "________ Area" using `git add`.
7. You want to stage every single modified file in your current folder instantly. The fastest command is `git add .` or `git add -________`.
8. Once files are staged, you permanently save a snapshot of them into history using `git ________ -m "Message"`.
9. If you forgot to include a file, but you already committed, you can stage the missed file and add it to your previous commit using `git commit --________`.
10. To view the chronological history of all saves sorted by date, type `git ________`.
11. If that history is way too long to read, you can compress each save into a single line of text by adding the flag `________`.
12. A commit is uniquely identified by a massive 40-character string of letters and numbers called a SHA-1 ________.
13. You accidentally staged a massive video file using `git add`. To unstage it *without* deleting the physical file from your computer, you type `git ________ Video.mp4`.
14. If you made a terrible mistake in `index.php` and haven't committed yet, you can instantly throw away your changes and revert the file back to its last saved state using `git ________ index.php`. (Modern Git).
15. If your folder is full of random temporary files that Git isn't tracking, you can instantly delete them all from your hard drive using `git ________ -fd`.
16. The specific configuration file determining local Git behavior inside your project is located at `.git/________`.
17. The command `git diff` shows you the exact lines of code that changed in your "Working Directory". To see the lines of code you *already* staged, run `git diff --________`.
18. When modifying text, Git shows deleted lines in red starting with a minus (`-`), and new lines in green starting with a `________` symbol.
19. Git doesn't automatically track completely empty folders. To trick Git into tracking an empty directory, developers usually create a hidden empty file inside it named `.________`.
20. In Git terminology, the very top commit of your current active branch is referred to as the `________` pointer.

---

## Phase 2: Branching & Merging
21. The default main branch in modern Git is called `________` (previously named `master`).
22. To create a brand new branch named "feature-login", you type `git ________ feature-login`.
23. Creating a branch doesn't move you there. In older Git versions, you move to it using `git checkout feature-login`. In modern Git (v2.23+), the preferred command is `git ________ feature-login`.
24. You can create a branch and immediately jump to it in one single command: `git checkout -________ feature-login`.
25. To see a list of all local branches on your computer, simply type `git ________`.
26. The branch you are currently sitting on is indicated in the list with a green `________` symbol.
27. You finished coding "feature-login" and want to combine it back into "main". First, you must switch back to the `________` branch.
28. Then, you combine the code by executing `git ________ feature-login`.
29. If nobody else edited "main" while you were working on your feature, Git simply pulls the pointer forward incredibly fast. This is called a `________-forward` merge.
30. If you edited line 10 on "main", and your coworker edited line 10 on "feature-login", attempting to combine them throws a terrifying warning called a Merge `________`.
31. When this happens, Git physically injects strange syntax into your code like `<<<<<<< HEAD`. You must delete these markers and save the file to `________` the issue.
32. After fixing the broken lines, you finalize the combination by running `git add` and then `git ________`.
33. After successfully combining "feature-login", you no longer need the branch. You can delete it using `git branch -________ feature-login`.
34. If you try to delete a branch that hasn't been combined yet, Git stops you. You can force-delete it anyway using a capital `________`.
35. Sometimes you want to quickly save your unfinished code without making an ugly commit so you can switch branches. You can temporarily "hide" your code perfectly using `git ________`.
36. When you switch back to your branch later, you can retrieve your hidden code and delete it from the hidden clipboard using `git stash ________`.
37. If you just want to see what is currently hidden in your clipboard without retrieving it, use `git stash ________`.
38. You can rename the current branch you are sitting on using `git branch -________ new-name`.
39. When you merge, Git usually creates a "Merge Commit". To prevent this and force Git to rewrite history to look like a perfectly straight line, you use `git ________` instead of merging.
40. Be warned! You should NEVER use the command above on branches that have already been pushed to `________` servers, because rewriting public history will break your coworkers' computers!

---

## Phase 3: Remotes, GitHub & Collaboration
41. To download a complete copy of an existing repository from GitHub to your computer, you use `git ________ https://github.com/...`.
42. A downloaded repository automatically creates a connection back to the GitHub server. By default, Git names this connection `________`.
43. To see the names of the servers your computer is connected to, type `git remote -________`.
44. If you initialized a Git repo locally and want to connect it to an empty GitHub repository, you type `git remote ________ origin https...`.
45. To upload your saved commits to the server, you type `git ________`.
46. If it's your first time uploading a new branch named "feature", you must tell GitHub to track it by adding a flag: `git push -________ origin feature`.
47. To download your coworkers' newest code from the server *without* automatically merging it into your files yet, use `git ________`.
48. To download their code AND immediately merge it into your active files, use `git ________`.
49. If you try to push your code, but someone else pushed code 5 minutes before you, your terminal will throw an error saying your branch is `________`. You must download their code first.
50. Your coworker deletes a branch on GitHub. Your local computer secretly still thinks that branch exists on the server. You can tell your computer to prune dead downloaded branches using `git fetch --________`.
51. If you push code, realize it broke production, and want to force GitHub to accept your older, working version (potentially destroying your coworkers' saves), you type `git push --________`.
52. If you want to grab exactly ONE specific commit from a coworker's branch and copy it to your branch without merging the whole thing, use `git ________`.
53. You can attach a permanent name to a specific commit (like "v1.0.0" for a software release). This is called creating a `________`.
54. To upload your specific release versions to GitHub, standard pushing doesn't work. You must explicitly type `git push --________`.
55. If you want to download a massive repository but skip downloading the 15-year history of every commit to save time and disk space, you run an incredibly shallow download: `git clone --depth ________`.
56. To see a list of all branches that exist physically on the GitHub server (even ones not on your computer yet), type `git branch -________`.
57. A "Pull Request" (PR) is not a native Git command. It is a feature invented by platforms like GitHub and GitLab that allows humans to physically ________ your code before allowing it to merge.
58. When cloning over SSH instead of HTTPS, Git uses cryptographic key pairs instead of passwords. This relies on the `_________rsa` (or ed25519) public key mechanism.
59. If you want to download a completely different repository into a sub-folder of your current project (like downloading an external plugin), you use Git `________`.
60. If you clone a project that contains the feature from question 59, the folders will be completely empty unless you immediately run `git submodule update --________`.

---

## Phase 4: Time Travel & Fixing Disasters
61. You wrote terrible code and hit commit. You haven't pushed yet. You want to undo the commit but *keep* the code in your text editor so you can fix it. You type `git ________ --soft HEAD~1`.
62. You want to undo the commit AND completely destroy the terrible code so your files look exactly like they did yesterday. You type `git reset --________ HEAD~1`.
63. You pushed terrible code to GitHub. You cannot use `reset` because it rewrites public history. Instead, you create a brand new commit that mathematically perfectly does the exact opposite of the terrible commit using `git ________ <commit-hash>`.
64. You committed 5 times, but realized you were on the `main` branch instead of a feature branch! To move the pointer back 5 commits without losing your code, you type `git reset --________ HEAD~5`.
65. You want to see exactly who wrote the terrible code on line 42 of `index.php` and on what date. You shame the developer by running `git ________ index.php`.
66. You can pinpoint exactly which commit caused a bug using a binary search algorithm. Git will automatically checkout old commits one by one and ask you "Is the bug here?" This wizardry is triggered via `git ________ start`.
67. You deleted a branch named "feature-cart" that had 3 weeks of unmerged work, and you emptied your computer's trash. It's gone forever... right? Wrong. Git secretly logs every single movement of the `HEAD` pointer. You can find the ghost hash using `git ________`.
68. If you want to change the text message of the very last commit you just made, run `git commit --________ -m "New Message"`.
69. You can permanently delete a file from Git's tracking history (like an accidentally uploaded database password) using `git rm --________ .env`.
70. If you want to compress 10 messy commits ("fix typo", "fix typo 2", "wip") into one single beautiful commit before merging, you can do an "Interactive Rebase" by typing `git rebase -________ HEAD~10`.
71. Inside the interactive rebase window, you change the word "pick" to `________` (or `s`) to combine that commit into the one above it.
72. If you want to delete a specific un-pushed commit from the middle of your history during an interactive rebase, you change the word "pick" to `________`.
73. You want to safely download code from an alternate remote (like the original open-source project you forked from). You generally name this secondary connection `________`.
74. `HEAD~3` means "Go back 3 commits." A similar syntax is `HEAD^`. How far back does `HEAD^` go? `________` commit(s).
75. If you do `git reset --hard` but instantly realize you needed that code, what is the *only* Git command that holds the lost hash allowing you to rescue it? `git ________`.
76. Sometimes you just want to grab a single file from a different branch without merging anything. You can extract it using `git checkout other-branch -- ________`.
77. You can search the entire Git history for a specific word (like a deleted function name) using `git ________ "FunctionName"`.
78. If you have uncommitted changes but pull from GitHub anyway, Git usually throws an error. You can force Git to hide your changes, pull, and then automatically re-apply your changes on top using `git pull --________`.
79. To legally enforce that every commit pushed to GitHub is actually from you (to prevent identity spoofing), you can sign your commits using a `________` key.
80. If you type `git commit` without `-m`, Git will suddenly trap you inside a terminal text editor. If it defaults to Vim, the absolute fastest way to save and exit is pressing ESC, then typing `________` and hitting Enter.

---

## Phase 5: Arcane Commands & Server Architecture
81. `git archive` is used to export your repository into a highly compressed `________` or `tar` file without including the massive hidden `.git` folder.
82. If your `.git` folder grows to 5 Gigabytes because of old detached commits, you can force Git to run a "Garbage Collection" optimization and delete orphans by typing `git ________`.
83. To display the Git log as a beautiful ascii-art branch tree in your terminal, you apply the flag: `git log --________`.
84. By default, Git uses a fast algorithm to compress files called `________` (which handles the creation of packfiles).
85. If you push code to a live production server, you technically don't run `git pull` on the server. Developers often configure a `post-________` script inside the `.git/hooks` folder to automatically deploy code when it receives a push.
86. A bare repository (one hosted on a server like GitHub that you can't type code directly into) does not have a "Working Directory". It is conventionally initialized using `git init --________`.
87. If you accidentally cloned a repository via HTTPS but you want to switch to SSH to stop typing your password, you use the command `git remote set-________ origin git@github.com...`.
88. When `git rebase` hits a conflict, you fix the code, type `git add .`, and then continue the process by typing `git rebase --________`.
89. If a merge conflict is so awful that you want to simply give up and return your files to normal, abort the process using `git merge --________`.
90. You can temporarily override your global Git name for just one single repository by dropping the `--global` flag. So you'd type: `git config ________ user.name "Work Name"`. (Hint: It defaults to this if you leave it completely blank).
91. You can write your own custom Git commands! If you create a bash script named `git-deploy` and put it in your system PATH, you can run it simply by typing `git ________`.
92. The `git rev-parse HEAD` command is heavily used in CI/CD pipelines (like GitHub Actions) because it prints out the exact raw `________` hash of your current branch.
93. If your team demands you format your commit messages in a specific way (e.g. `[Ticket-Number]: Message`), you can enforce this locally by writing a script in `.git/________/commit-msg`.
94. Git completely ignores empty folders. To keep an empty folder (like `storage/logs/`) in version control, the generic standard is creating an empty file inside it named `.________`.
95. If you type `git push origin main`, the word `origin` represents the server, and `main` represents the `________`.
96. The internal Git command that actually writes raw physical blobs to the object database (rarely used by normal humans) is called `git hash-________`.
97. If you only want to stage exactly lines 10 through 15 of `index.php` while leaving lines 20 through 30 unstaged in your Working Directory, you can selectively stage "hunks" by running `git add -________`.
98. Git tracks changes, but a tool called `Git ________` (LFS) replaces large 3D models or audio files with tiny text pointers to prevent your repository from slowing down.
99. Your Git configuration has a hidden superpower: Aliases. You can make `git st` automatically trigger `git status` by running `git config --global ________.st status`.
100. Congratulations! You've reached the end. Always remember: No matter how badly you destroyed your codebase, as long as you successfully ran `git ________` before you broke it, your code is mathematically safe and recoverable forever.

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

---
### The Master Key (Answers)

1. init
2. git
3. user
4. gitignore
5. status
6. Staging / Index
7. A (or all)
8. commit
9. amend
10. log
11. --oneline
12. hash (or identifier)
13. restore --staged (or reset HEAD)
14. restore (or checkout --)
15. clean
16. config
17. staged (or cached)
18. plus (+) 
19. gitkeep (or keep)
20. HEAD
21. main
22. branch
23. switch
24. b
25. branch
26. asterisk (*)
27. main
28. merge
29. fast
30. Conflict
31. resolve
32. commit
33. d
34. D
35. stash
36. pop
37. list
38. m (or M)
39. rebase
40. public / remote
41. clone
42. origin
43. v (verbose)
44. add
45. push
46. u (or set-upstream)
47. fetch
48. pull
49. behind / out of date
50. prune
51. force (or f)
52. cherry-pick
53. tag
54. tags
55. 1
56. r (or a / all)
57. review
58. id
59. submodule
60. init
61. reset
62. hard
63. revert
64. mixed (or soft)
65. blame
66. bisect
67. reflog
68. amend
69. cached
70. i (interactive)
71. squash
72. drop (or d)
73. upstream
74. 1
75. reflog
76. filename (or path)
77. log -S (or grep)
78. rebase
79. GPG / SSH
80. :wq (or :x)
81. zip
82. gc
83. graph
84. zlib (or deflate)
85. receive
86. bare
87. url
88. continue
89. abort
90. local
91. deploy
92. SHA / SHA-1
93. hooks
94. gitkeep
95. branch
96. object
97. p (or patch)
98. Large File Storage
99. alias
100. commit
