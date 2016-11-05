<?php

$validator = [
    'required'          =>"<p>The <b>{label}</b> field is required</p>",
    'alphabetic'        =>"<p>The <b>{label}</b> field must contain only alphabetic (a-z,A-Z) characters</p>",
    'trim'              =>"<p>The <b>{label}</b> field can not contain only whitespaces</p>",
    'ipAddress'         =>"<p>The <b>{label}</b> field must contain valid IP address</p>",
    'numeric'           =>"<p>The <b>{label}</b> field must contain numeric characters</p>",
    'linkURL'           =>"<p>The <b>{label}</b> field must contain valid URL ex( http://test.com )</p>",
    'createdAt'         =>"<p>The <b>{label}</b> field must contain valid date value</p>",
    'UpdatedAt'         =>"<p>The <b>{label}</b> field must contain valid date value</p>",
    'maxLength'         =>"<p>The <b>{label}</b> field can contain maximum <b>{start}</b> characters</p>",
    'minLength'         =>"<p>The <b>{label}</b> field must contain at less <b>{start}</b> characters</p>",
    'strongPassword'    =>"<p>The <b>{label}</b> field must contain at less one lowercase one uppercase and one numeric characters</p>",
    'email'             =>"<p>The <b>{label}</b> field must contain valid email address ex( testemail@test.com )</p>",
    'validEmail'        =>"<p>The <b>{label}</b> field must contain valid email address ex( testemail@test.com )</p>",
    'between'           =>"<p>The <b>{label}</b> field must contain characters between <b>{start}</b> and <b>{end}</b></p>",
    'matchWith'         =>"<p>The <b>{label}</b> field must be same with <b>{other}</b> filed</p>"
];