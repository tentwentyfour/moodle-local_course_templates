# moodle-local_course_templates

With this moodle plugin you can easily create courses based on course templates. The plugin also can be used to easily duplicate courses.

## Usage

When creating a "Course Templates" category, you may hide the catgory, but you may not hide the courses themselves. Hidden courses cannot be duplicated, because they will throw a `Course or activity not accessible. (Course is hidden)` error.

## Permissions

__Note__: Users (or roles) who should be allowed to create courses from templates need the following permissions:

* `moodle/course:view`
* `moodle/restore:restorecourse`
* `moodle/backup:backupcourse`
* `moodle/backup:userinfo`
* `moodle/restore:userinfo`

* backup user setting perms ?!

Other than that the following permission local to this plug-in are required:

* `local/course_templates::use` - To be able to create new courses from templates
* `local/course_templates::view` - To … well, it's used in process.php as a general check (can probably be replaced by the use permission)

### Todo

* use constants instead of numeric codes for status (cf. process.php, index.php)


### Permissions Details

#### moodle/course:view

`course:view` is checked on duplicate_course in moodlelib::require_login
`lib/moodlelib.php::require_login`:

```php
} else if (is_viewing($coursecontext, $USER)) {
```

#### moodle/restore:restorecourse

TDB

#### moodle/backup:backupcourse

TDB

// Capability checking.

        // The backup controller check for this currently, this may be redundant.
        require_capability('moodle/course:create', $categorycontext);
        require_capability('moodle/restore:restorecourse', $categorycontext);
        require_capability('moodle/backup:backupcourse', $coursecontext);

        if (!empty($backupsettings['users'])) {
            require_capability('moodle/backup:userinfo', $coursecontext);
            require_capability('moodle/restore:userinfo', $categorycontext);
        }
