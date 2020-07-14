<div class="m-content">
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--creative m-portlet--bordered-semi">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                <span>{{$page_title}}</span>
                            </h2>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">

                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$image = str_replace('data:image/png;base64,', '', $request->photo);
$image = str_replace(' ', '+', $image);
$filenameWithExt = $request->file('photo')->getClientOriginalName(); // Get filename with extension
$filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME); // Get just filename            
$extension       = $request->file('photo')->getClientOriginalExtension(); // Get just extension
$fileNameToStore = $id.'-'.$filename.'.'.'png'; //Filename to store 
// $imageName = str_random(10).'.'.'png';
$target_file = $target_dir.$fileNameToStore;
$userdata->photo = $fileNameToStore;
// \File::put(storage_path(). '/' . $imageName, base64_decode($image));
file_put_contents($target_file, base64_decode($image));
?>