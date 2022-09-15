<?php
    require_once('includes/header.php');
    require_once('config/connect.php');
?>
  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
<?php
    require_once('layouts/header.php');
?>
    <section class="breadcrumbs mb-">
      <!--<div class="container">-->

      <!--  <ol>-->
      <!--    <li><a href="index.php">Home</a></li>-->
      <!--    <li>Blog</li>-->
      <!--  </ol>-->
      <!--  <h2>Blog</h2>-->

      <!--</div>-->
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
      <div class="container" data-aos="fade-up">

        <div class="row">

          <div class="col-lg-6 entries py-2 px-1">
            <?php
            $query = mysqli_query($link, "SELECT * FROM `blog`");
            while($results = mysqli_fetch_assoc($query)) { ?>

            <article class="entry">

              <div class="entry-img">
                <img src="<?php echo $results['image_path']; ?>" alt="<?php if($results['image_path']==""){echo "";} ?>" class="img-fluid">
              </div>

              <h2 class="entry-title">
                <a href="blog-single.php?id=<?php echo $results['id'] ?>"><?php echo $results['title']; ?></a>
              </h2>

              <div class="entry-meta">
                <ul>
                  <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="blog-single.php?id=<?php echo $results['id']; ?>"><time datetime="<?php echo $results['datecreated']; ?>"><?php echo date('d M, Y', strtotime($results['datecreated'])); ?></time></a></li>
                </ul>
              </div>

              <div class="entry-content">
                <?php $text = substr($results['content'], 0, 500); echo $text."...";  ?>
                <div class="read-more">
                  <a class='ms-lg-auto' href="blog-single.php?id=<?php echo $results['id']; ?>">Read More</a>
                </div>
              </div>

            </article><!-- End blog entry -->
          <?php }?>

          </div><!-- End blog entries list -->

          <div class="col-lg-4 ms-lg-auto">

            <div class="sidebar col-lg-10 ">

              <h3 class="sidebar-title">Search</h3>
              <div class="sidebar-item search-form">
                <form action="">
                  <input type="text">
                  <button type="submit"><i class="bi bi-search"></i></button>
                </form>
              </div><!-- End sidebar search formn-->

              <h3 class="sidebar-title">Recent Posts</h3>
              <div class="sidebar-item recent-posts">
                <?php
                $query2 = mysqli_query($link, "SELECT * FROM `blog`");
                while($result2 = mysqli_fetch_assoc($query2)) { ?>
                <div class="post-item clearfix">
                  <img src="<?php if($result2['image_path']=="") {echo "assets/img/no_image.png";} else {echo $result2['image_path'];} ?>" alt="">
                  <h4><a href="blog-single.php?id=<?php echo $result2['id']; ?>"><?php echo $result2['title']; ?></a></h4>
                  <time datetime="<?php echo $result2['datecreated']; ?>"><?php echo date('d M, Y', strtotime($result2['datecreated'])); ?></time>
                </div>
              <?php } ?>

              </div><!-- End sidebar recent posts-->

            </div><!-- End sidebar -->

          </div><!-- End blog sidebar -->

        </div>

      </div>
    </section><!-- End Blog Section -->

  </main><!-- End #main -->

  <?php
    require_once('includes/footer.php');
?>



------------------session_save_path

<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("login");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $agurl = "fetch_admin_by_string_api.php?string=".$session->verify_string;
    $admins = perform_get_curl($agurl);
    if($admins){
        if($admins->status == "success"){
            $admin = $admins->data;
        } else {
            die($admins->message);
        }
    } else {
        die("Admin Link Broken");
    }
    
    if(isset($_POST['submit'])){
        $title = !empty($_POST['title']) ? (string)$_POST['title'] : "";
        $author = !empty($_POST['author']) ? (string)$_POST['author'] : "";;
        $content = !empty($_POST['content']) ? (string)$_POST['content'] : "";
        $admin_string = !empty($session->verify_string) ? (string)$session->verify_string : "";

        $purl = "add_blog_post_api.php";
        $pdata = [
                'title' => $title,
                'author' => $author,
                'post' => $content,
                'admin_string' => $admin_string
            ];
        $add_blog = perform_post_curl($purl, $pdata);
        if($add_blog){
            if($add_blog->status == "success"){
                $response = $add_blog->data;
                $session->message("Blogost has been added successfully");
                $message = "Blogost has been added successfully";
            } else {
                $message = $add_blog->message;
                
            }
        } else {
            $message = "Something went wrong";
        }
    } else {
        $title =  "";
        $author = "";
        $content = "";
    }


    
    include_admin_template("header.php");
?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi, <?php echo $admin->name; ?></h4>
                            <p class="mb-0">Make a blog post today</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"><a href="javascript:void(0)"><i class="fa fa-home"></i></a></li>
                        </ol>
                    </div>
                </div>
                
                <!-- <div id="editor" class=""></div> -->
                <form action="" method="post">
                    <div class="d-flex flex-column mb-2">
                        <label for="title" class="form-label text-primary mb-0 font-weight-bold">Title of Blog:</label>
                        <input class="form-control mb-1" type="text" name="title" id="title" placeholder="Blog Title">
                        <label for="author" class="form-label text-primary mb-0 font-weight-bold">Name of Author:</label>
                        <input class="form-control" type="text" name="author" id="author" placeholder="Author">
                    </div>
                    <label for="content" class="form-label text-primary mb-0 font-weight-bold">Post details:</label>
                    <textarea name="content" id="editor">
                        &lt;p&gt;This is some sample content.&lt;/p&gt;
                    </textarea>
                    <p><input class="btn btn-primary btn mt-2" type="submit" name="submit" value="Submit"></p>
                    <?php 
                        if(!empty($message)){
                            echo "<p class='text-danger'>{$message}</p>";
                        }
                    ?>
                </form>
            </div>
        </div>
        <!-- <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script> -->
        <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/super-build/ckeditor.js"></script>
        <!--
            Uncomment to load the Spanish translation
            <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/super-build/translations/es.js"></script>
        -->
        <script>
            CKEDITOR.replace('ckEditorRaw', { enterMode: CKEDITOR.ENTER_BR });
            ckEditorInstance = CKEDITOR.instances.ckEditorRaw;
            // This sample still does not showcase all CKEditor 5 features (!)
            // Visit https://ckeditor.com/docs/ckeditor5/latest/features/index.html to browse all the features.
            CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
                // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
                toolbar: {
                    items: [
                        'exportPDF','exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                // Changing the language of the interface requires loading the language file using the <script> tag.
                // language: 'es',
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
                placeholder: 'Welcome to CKEditor 5!',
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
                fontSize: {
                    options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                    supportAllValues: true
                },
                // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
                // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                },
                // Be careful with enabling previews
                // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
                htmlEmbed: {
                    showPreviews: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }
                    ]
                },
                // The "super-build" contains more premium features that require additional configuration, disable them below.
                // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
                removePlugins: [
                    // These two are commercial, but you can try them out without registering to a trial.
                    // 'ExportPdf',
                    // 'ExportWord',
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                    // Storing images as Base64 is usually a very bad idea.
                    // Replace it on production website with other solutions:
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                    // 'Base64UploadAdapter',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                    // from a local file system (file://) - load this site via HTTP server if you enable MathType
                    'MathType'
                ]
            });
        </script>

<?php include_admin_template("footer.php"); ?>

-------openssl_decrypt <a href="blog?<?php echo $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $name.".html"; ?>" class="col-12 col-md-6 col-lg-4 mb-4 p-0 p-2 rounded-top">
                    echo "<div >";
                      echo "<div class='rounded-top blog-card'>";
                        echo "<div class='blog-img rounded-top bg-primary'></div>";
                        echo "<div class='d-flex flex-column justify-content-between blog-body'>";
                          echo "<div class=''>";
                              echo "<div class='blog-title'>";
                                  echo "<span>".$blog->title ."<span/>";
                              echo "</div>";
                              echo "<div class='blog-text'>";
                                  echo formatString($blog->post);
                              echo "</div>";
                          echo "</div>";
                          echo "<div class='text-end'></i>"
                            .'<svg width="30" height="20" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0 4.99965C0 4.86705 0.0526784 4.73987 0.146447 4.6461C0.240215 4.55233 0.367392 4.49965 0.5 4.49965H12.293L9.146 1.35366C9.05211 1.25977 8.99937 1.13243 8.99937 0.999655C8.99937 0.866879 9.05211 0.739542 9.146 0.645655C9.23989 0.551768 9.36722 0.499023 9.5 0.499023C9.63278 0.499023 9.76011 0.551768 9.854 0.645655L13.854 4.64565C13.9006 4.6921 13.9375 4.74728 13.9627 4.80802C13.9879 4.86877 14.0009 4.93389 14.0009 4.99965C14.0009 5.06542 13.9879 5.13054 13.9627 5.19129C13.9375 5.25203 13.9006 5.30721 13.854 5.35365L9.854 9.35365C9.76011 9.44754 9.63278 9.50029 9.5 9.50029C9.36722 9.50029 9.23989 9.44754 9.146 9.35365C9.05211 9.25977 8.99937 9.13243 8.99937 8.99965C8.99937 8.86688 9.05211 8.73954 9.146 8.64565L12.293 5.49965H0.5C0.367392 5.49965 0.240215 5.44698 0.146447 5.35321C0.0526784 5.25944 0 5.13226 0 4.99965V4.99965Z" fill="gray"/></svg></div>';
                        echo "</div>";
                      echo "</div>";
                    echo "</div>";
                  echo "</a>"
            ?>


            ----------------------dashboard
            <?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("login");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $agurl = "fetch_admin_by_string_api.php?string=".$session->verify_string;
    $admins = perform_get_curl($agurl);
    if($admins){
        if($admins->status == "success"){
            $admin = $admins->data;
        } else {
            die($admins->message);
        }
    } else {
        die("Admin Link Broken");
    }
    
    $ugurl = "fetch_all_users_api.php";
    $users = perform_get_curl($ugurl);
    if($users){
        if($users->status == "success"){
            $user_count = count($users->data);
        } else {
            $user_count = 0;
        }
    } else {
        die("Users Link Broken");
    }
    
    $bgurl = "fetch_all_businesses_api.php";
    $businesses = perform_get_curl($bgurl);
    if($businesses){
        if($businesses->status == "success"){
            $bus_count = count($businesses->data);
        } else {
            $bus_count = 0;
        }
    } else {
        die("Businesess Link Broken");
    }
    
    include_admin_template("header.php");
?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi, <?php echo $admin->name; ?></h4>
                            <p class="mb-0">Your Yenreach Admin Dashboard</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"><a href="javascript:void(0)"><i class="fa fa-home"></i></a></li>
                        </ol>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-primary">Users</h3>
                            </div>
                            <div class="stat-widget-one card-body">
                                <div class="stat-icon d-inline-block">
                                    <i class="fa fa-users text-primary border-primary"></i>
                                </div>
                                <div class="stat-content d-inline-block">
                                    <div class="stat-digit text-primary">Total | <?php echo $user_count; ?></div>
                                    <div class="stat-text"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-primary">Businesses</h3>
                            </div>
                            <div class="stat-widget-one card-body">
                                <div class="stat-icon d-inline-block">
                                    <i class="fa fa-building text-primary border-primary"></i>
                                </div>
                                <div class="stat-content d-inline-block">
                                    <div class="stat-digit text-primary">Total | <?php echo $bus_count; ?></div>
                                    <div class="stat-text"><a href="all_businesses">All Businesses</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php include_admin_template("footer.php"); ?>
