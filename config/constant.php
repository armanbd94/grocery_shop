<?php
define('GENDER',json_encode(['','Male','Female']));
define('ACCOUNT_STATUS',json_encode(['','Active','Inactive'])); /* :: ACCOUNT ACTIVE OR INACTIVE STATUS :: */
define('DEFAULT_PROFILE_IMAGE',json_encode(['',
'public/uploads/profile-img/male.png', /* :: FOR MALE USER DEFAULT PROFILE PHOTO PATH :: */
'public/uploads/profile-img/female.png' /* :: FOR FEMALE USER DEFAULT PROFILE PHOTO PATH :: */
]));/* :: USER DEFAULT PROFILE PHOTO PATH :: */
define('DEFAULT_PHOTO','public/website-assets/images/profile-photo/default.jpg'); /* :: USER PROFILE PHOTO PATH CONSTANT :: */
define('USER_PROFILE_PHOTO','public/uploads/profile-img/'); /* :: DASHBOARD USER PROFILE PHOTO FOLDER PATH :: */
define('CUSTOMER_PROFILE_PHOTO','public/uploads/customer/');
define('LOGO_PATH','public/uploads/logo/'); /* :: SITE LOGO FOLDER PATH :: */

define('BANNER_IMAGE_PATH', 'public/uploads/banner/'); /* :: WEBSITE HOME PAGE BANNER IMAGE FOLDER PATH :: */
define('NECESSARY_IMAGE_PATH', 'public/uploads/necessary-images/'); /* :: OTHER NECCESSARY IMAGE FOLDER PATH :: */
define('LOADING_ICON','public/website-assets/img/lazy.svg');
define('PRODUCT_IMAGE_PATH', 'public/uploads/products/'); /* PRODUCT UPLOADING IMAGE PATH */

define('UPLOAD_PATH','public/uploads/'); /* :: UPLOAD PATH :: */
define('NO_IMAGE','public/dashboard-assets/img/no-image-available.gif'); /* :: DASHBOARD NO DATA FOUND IMAGE FOLDER PATH :: */
define('NO_DATA','public/dashboard-assets/img/no-data-available.gif'); /* :: DASHBOARD NO IMAGE FOUND IMAGE FOLDER PATH :: */
define('DATE', date('Y-m-d H:i:s')); /* :: DATABASE DATE FORMAT :: */
define('STATUS', json_encode(['','Enable','Disable'])); /* :: ENABLE OR DISABLE STATUS :: */
define('MSG_STATUS', json_encode(['','Seen','Unseen'])); /* :: Seen OR Unseen STATUS :: */
define('ORDER_STATUS', json_encode(['','Confirmed','Canceled'])); /* :: Confirmed OR Canceled Order STATUS :: */
define('DELIVERY_STATUS', json_encode(['','Delivered','Pending'])); /* :: Pending OR Delivered Delivery STATUS :: */
define('STATUS_TYPE',json_encode(['','m-badge--success','m-badge--danger'])); /* :: LABEL COLOR :: */
define('NO_IMAGE_AVAILABLE','public/uploads/no-image-available.png');

define('PRODUCT_SLUG_PREFIX', 1000);
?>