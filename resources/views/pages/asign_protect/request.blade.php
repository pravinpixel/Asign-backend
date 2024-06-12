@extends('layouts.index')
@section('title', 'Asign Request')
@section('style')
@parent
<link rel="stylesheet" type="text/css" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('css/select2.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('css/select2-material.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('css/rcrop.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.viewbox.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('css/demo/asign_protect_request.css')}}" />
@endsection
@section('content')
<div class="pages asign-request">
    <section class="alt-header">
        <div class="alt-header-left">
            <h4>Request ID: 0004 <span class="asign_protect_plus">Authentication</span></h4>
            {{-- authentication / inspection / asign_protect_plus --}}
            <p>Customer ID: 2345</p>
        </div>
        <div class="alt-header-right">
            <div class="hstack gap-1">
                <div class="p-1">
                    <button type="button" class="btn btn-outline-secondary btn-lg">Reject</button>
                </div>
                <div class="p-1">
                    <button type="button" class="btn btn-dark btn-lg">Approve</button>
                </div>
            </div>
        </div>
    </section>
    <section class="alt-section">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">REQUEST</button>
                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">PROVENANCE</button>
                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">OBJECT DETAILS</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                <section class="section-inner">
                    <ul class="personal-info">
                        <li>
                            <span>Customer Name</span>
                            <span>Vikram Singh</span>
                        </li>
                        <li>
                            <span>Customer Type</span>
                            <span>Artist</span>
                        </li>
                        <li>
                            <span>Phone Number</span>
                            <span>(+91) 9876654321</span>
                        </li>
                        <li>
                            <span>Address</span>
                            <span>Art Complex, 1, Kumarakrupa Rd, near The Lalit Hotel, Kumara Park East, Seshadri Puram, Bengaluru, Karnataka 560001</span>
                        </li>
                        <li>
                            <span data-bs-toggle="modal" data-bs-target="#rejectProtectModal">Reject</span>
                            <span></span>
                        </li>
                        <li>
                            <span data-bs-toggle="modal" data-bs-target="#rejectProtectReasonModal">Reject Alt</span>
                            <span></span>
                        </li>
                    </ul>
                </section>
                <section class="section-inner top-border">
                    <h1>Team</h1>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <ul class="personal-info vertics grey-txt">
                                <li>
                                    <span>Authenticator</span>
                                    <span class="pinkish">
                                        <select multiple placeholder="Add Primary Authenticator" data-allow-clear="1" data-color="pinkish">
                                            <option value="Aman Tyagi">Aman Tyagi</option>
                                            <option value="Rishi Shankar">Rishi Shankar</option>
                                            <option value="Rohan Sharma">Rohan Sharma</option>
                                            <option value="Samay Raina">Samay Raina</option>
                                            <option value="Varun Mahapatra">Varun Mahapatra</option>
                                        </select>
                                    </span>
                                </li>
                                <li>
                                    <span>Conservator</span>
                                    <span class="violets">
                                        <select multiple placeholder="Add Conservators" data-allow-clear="1" data-color="pinkish">
                                            <option value="Kumar Rao">Kumar Rao</option>
                                            <option value="Mahira Kapoor">Mahira Kapoor</option>
                                            <option value="Mihir Shresth">Mihir Shresth</option>
                                            <option value="Shanaya Kapoor">Shanaya Kapoor</option>
                                            <option value="Shanaya Kapoor">Shanaya Kapoor</option>
                                        </select>
                                    </span>
                                </li>
                                <li>
                                    <span>Inspection Date</span>
                                    <span>
                                        <input type="text" class="datepicker form-control" name="date" id="date" placeholder="Select Date">
                                    </span>
                                </li>
                                <li>
                                    <span>Inspection Time</span>
                                    <span>
                                        <input type='time' class='timepicker form-control' name="time" data-placeholder="Select Time">
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6">
                            <ul class="personal-info vertics grey-txt">
                                <li>
                                    <span>Field Agents</span>
                                    <span class="pinkish">
                                        <select multiple placeholder="Add Field Agents" data-allow-clear="1">
                                            <option value="Aman Tyagi">Aman Tyagi</option>
                                            <option value="Rishi Shankar">Rishi Shankar</option>
                                            <option value="Rohan Sharma">Rohan Sharma</option>
                                            <option value="Samay Raina">Samay Raina</option>
                                            <option value="Varun Mahapatra">Varun Mahapatra</option>
                                        </select>
                                    </span>
                                </li>
                                <li>
                                    <span>Service Provider</span>
                                    <span class="violets">
                                        <select multiple placeholder="Add Service Provider" data-allow-clear="1">
                                            <option value="Kumar Rao">Kumar Rao</option>
                                            <option value="Mahira Kapoor">Mahira Kapoor</option>
                                            <option value="Mihir Shresth">Mihir Shresth</option>
                                            <option value="Shanaya Kapoor">Shanaya Kapoor</option>
                                            <option value="Shanaya Kapoor">Shanaya Kapoor</option>
                                        </select>
                                    </span>
                                </li>
                                <li>
                                    <span>Visit Date</span>
                                    <span>
                                        <input type="text" class="datepicker form-control" name="date" id="date1" placeholder="Select Date">
                                    </span>
                                </li>
                                <li>
                                    <span>Visit Time</span>
                                    <span>
                                        <input type='time' class='timepicker form-control' name="time" data-placeholder="Select Time">
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
                <section class="section-inner top-border">
                    <h1>Artwork</h1>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <article id='artwork_gallery'>
                                <div class="artwork_thumbs">
                                    <ul>
                                        <li><img src="{{asset('demo/one.png')}}" alt="one" /></li>
                                        <li><img src="{{asset('demo/two.png')}}" alt="two" /></li>
                                        <li><img src="{{asset('demo/three.png')}}" alt="three" /></li>
                                        <li><img src="{{asset('demo/four.png')}}" alt="four" /></li>
                                        <li><img src="{{asset('demo/two.png')}}" alt="two" /></li>
                                        <li><img src="{{asset('demo/three.png')}}" alt="three" /></li>
                                        <li><img src="{{asset('demo/four.png')}}" alt="four" /></li>
                                    </ul>
                                </div>
                                <div class="artwork_preview">
                                    <img src="{{asset('demo/preview.png')}}" alt="preview" />
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-md-6">
                            <article class="artwork_details">
                                <h5>Vikram Singh</h5>
                                <h1>Buffalo Trail: The Impending Storm, 1869</h1>
                                <p><img src="{{asset('icons/location_alt.png')}}" alt="" /> Bengaluru, Karnataka - 123009 </p>
                                <ul class="personal-info black-txt">
                                    <li>
                                        <span>About</span>
                                        <span>Object Type: Painting <br /> In possession: Yes</span>
                                    </li>
                                    <li>
                                        <span>Medium</span>
                                        <span>Material: Digital Print <br /> Surface: Mount board</span>
                                    </li>
                                    <li>
                                        <span>Primary Measurement</span>
                                        <span>
                                            Shape: Rectangle <br />
                                            Size: 76.2 x 63.5 x 63.2 cm (30 x 25 in.)<br />
                                            Diameter: 52 cm<br />
                                            Weight: 5kgs
                                        </span>
                                    </li>
                                </ul>
                            </article>
                        </div>
                    </div>
                </section>
                <section class="section-inner activity top-border">
                    <h1>Activity</h1>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <article>
                                <div class="mb-3 pos-rel">
                                    <label for="comments" class="form-label">
                                        Notes <span><span>as</span> Priyadarshani Patel(Regional Head)</span>
                                    </label>
                                    <textarea class="form-control" id="comments" rows="3" placeholder="Write a comment..."></textarea>
                                    <button type="button" class="btn btn-light comment-submit">Add Comment</button>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <article>
                                <ul>
                                    <li class="d-flex gap-3 mb-3">
                                        <div class="profile-div">
                                            <span class="profile-avatar">KR</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-1 profile-in">
                                            <span class="activity-name">Kumar Rao</span>
                                            <span class="activity-profile">Verified the Profile</span>
                                            <span class="dot mx-2 dot-activity"></span>
                                            <span class="activity-date">20 Nov, 2023</span>
                                        </div>
                                    </li>
                                </ul>
                            </article>
                        </div>
                    </div>
                </section>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                <section class="section-inner">
                    <h1>Provenance</h1>
                    <ol type="1" class="ol-info">
                        <li>Created in 2018 by artist Elena Rodriguez during a residency in Barcelona.</li>
                        <li>Initially acquired by Dr. Jonathan Harris at Rodriguez's solo exhibition in Madrid (2018).</li>
                        <li>Featured in group exhibitions, including the International Art Fair in Paris (2019).</li>
                        <li>Gallery representation by Gallery XYZ in New York (2020) for Rodriguez's solo show.</li>
                        <li>Currently owned by Ms. Olivia Thompson, acquired through Gallery XYZ in 2021.</li>
                    </ol>
                </section>
                <section class="section-inner-alt">
                    <h1>Auction History</h1>
                    @include('components.tables.asign_table')
                </section>
                <section class="section-inner-alt">
                    <h1>Exhibition History</h1>
                </section>
                <section class="section-inner-alt">
                    <h1>Publication History</h1>
                </section>
            </div>
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">
                <section class="section-inner">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="image_radio" id="image_radio">
                        <label class="form-check-label" for="image_radio">
                            Images
                        </label>
                    </div>
                    <article class="edit-wrapper">
                        <div class="image-preview">
                            <a href="{{asset('demo/light_1.jpg')}}" class="view_box" title="San Francisco">
                                <img src="{{asset('demo/light_1.jpg')}}" alt="" class="img-fluid">
                            </a>
                            <a href="{{asset('demo/light_2.jpg')}}" class="view_box" title="San Francisco">
                                <img src="{{asset('demo/light_2.jpg')}}" alt="" class="img-fluid">
                            </a>
                            <a href="{{asset('demo/light_3.jpg')}}" class="view_box" title="San Francisco">
                                <img src="{{asset('demo/light_3.jpg')}}" alt="" class="img-fluid">
                            </a>
                            <div class="upload-btn-wrapper">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                <span>Add Image</span>
                                <input type="file" name="image" class="image"/>
                            </div>
                        </div>
                    </article>
                </section>
                <section class="section-inner top-border">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="object_radio" id="object_radio">
                        <label class="form-check-label" for="object_radio">
                            Object Identification
                        </label>
                    </div>
                    <article class="edit-wrapper">
                        <ul class="personal-info black-txt">
                            <li>
                                <span>Asign Object Number</span>
                                <span>19203</span>
                            </li>
                            <li>
                                <span>Accession Number</span>
                                <span>1920.31</span>
                            </li>
                            <li>
                                <span>Inventory Label</span>
                                <span style="color:#CFCFCF">Pending</span>
                            </li>
                            <li>
                                <span>Authentication Label</span>
                                <span style="color:#CFCFCF">Pending</span>
                            </li>
                        </ul>
                    </article>
                </section>
                <section class="section-inner top-border">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="about_radio" id="about_radio">
                        <label class="form-check-label" for="about_radio">
                            About
                        </label>
                    </div>
                    <article class="edit-wrapper">
                        <ul class="personal-info black-txt">
                            <li>
                                <span>Artist Name</span>
                                <span>Vikram Singh</span>
                            </li>
                            <li>
                                <span>Object Title</span>
                                <span>Buffalo Trail: The Impending Storm, 1869</span>
                            </li>
                            <li>
                                <span>Object Type</span>
                                <span>Painting</span>
                            </li>
                            <li>
                                <span>Creation Year</span>
                                <span>2020</span>
                            </li>
                            <li>
                                <span>Completion Year</span>
                                <span>2021</span>
                            </li>
                            <li>
                                <span>In Possession</span>
                                <span>Yes</span>
                            </li>
                            <li>
                                <span>Description</span>
                                <span>By 1869, when he created this idyllic view, Albert Bierstadt had made two extensive trips to the American West. He based this lush scene of buffalo peacefully making their way across a river or creek against a roiling sky on views he had sketched during one or both of those expeditions.</span>
                            </li>
                        </ul>
                    </article>
                </section>
                <section class="section-inner top-border">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="medium_radio" id="medium_radio">
                        <label class="form-check-label" for="medium_radio">
                            Medium and Measurements
                        </label>
                    </div>
                    <article class="edit-wrapper">
                        <ul class="personal-info black-txt">
                            <div class="ul-title">Medium</div>
                            <li>
                                <span>Medium</span>
                                <span>Oil Paints</span>
                            </li>
                            <li>
                                <span>Surface</span>
                                <span>Mountboard</span>
                            </li>
                        </ul>
                        <ul class="personal-info black-txt">
                            <div class="ul-title">Measurements</div>
                            <li>
                                <span>Shape</span>
                                <span>Rectangle</span>
                            </li>
                            <li>
                                <span>Measurement Type</span>
                                <span>Centimetre (Cm)</span>
                            </li>
                            <li>
                                <span>Height</span>
                                <span>52 cm</span>
                            </li>
                            <li>
                                <span>Width</span>
                                <span>52 cm</span>
                            </li>
                            <li>
                                <span>Depth</span>
                                <span>52 cm</span>
                            </li>
                            <li>
                                <span>Diameter</span>
                                <span>52 cm</span>
                            </li>
                            <li>
                                <span>Weight</span>
                                <span>5kgs</span>
                            </li>
                        </ul>
                    </article>
                </section>
                <section class="section-inner top-border">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="charater_radio" id="charater_radio">
                        <label class="form-check-label" for="charater_radio">
                            Characteristics
                        </label>
                    </div>
                    <article class="edit-wrapper">
                        <ul class="personal-info black-txt">
                            <li>
                                <span>Technique</span>
                                <span>Pointillism</span>
                            </li>
                            <li>
                                <span>Style</span>
                                <span>Contemporary</span>
                            </li>
                            <li>
                                <span>Movement</span>
                                <span>Romanticism</span>
                            </li>
                            <li>
                                <span>Subject</span>
                                <span>TBD</span>
                            </li>
                        </ul>
                    </article>
                </section>
                <section class="section-inner top-border">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="sign_radio" id="sign_radio">
                        <label class="form-check-label" for="sign_radio">
                            Signature & Inscriptions
                        </label>
                    </div>
                    <article class="edit-wrapper">
                        <ul class="personal-info black-txt">
                            <li>
                                <span>Signature</span>
                                <span>Base</span>
                            </li>
                            <li>
                                <span>Description</span>
                                <span>This can be a very long description that the FA team writes about </span>
                            </li>
                            <li>
                                <span>Inscriptions</span>
                                <span>Yes</span>
                            </li>
                            <li>
                                <span>Verso</span>
                                <span>Stamp office Sangli State, 1st Jul 1935</span>
                            </li>
                            <li>
                                <span>Rector</span>
                                <span>STTRE-7 on the frame</span>
                            </li>
                            <li>
                                <span>Base</span>
                                <span>Type here</span>
                            </li>
                        </ul>
                    </article>
                </section>
                <section class="section-inner top-border">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="location_radio" id="location_radio">
                        <label class="form-check-label" for="location_radio">
                            Location
                        </label>
                        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                            <i class="fa fa-plus" aria-hidden="true"></i> ADD LOCATION
                        </button>
                    </div>
                    <article class="edit-wrapper-plain">
                        <div class="accordion accordion-flush custom-accordion" id="locationAccordion">
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <div class="accordion-header-alt">
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gallery_one" id="gallery_one">
                                            </div>
                                        </div>
                                        <div>
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#locationAccordion_tab_one" aria-expanded="false" aria-controls="locationAccordion_tab_one">
                                                Primary Gallery - Koramangala
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="locationAccordion_tab_one" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <ul class="personal-info black-txt">
                                            <li>
                                                <span>Sub-Location</span>
                                                <span>Koramangala 4th Block</span>
                                            </li>
                                            <li>
                                                <span>Address</span>
                                                <span>Art Complex, 1, Kumarakrupa Rd, near The Lalit Hotel, Kumara Park East, Seshadri Puram </span>
                                            </li>
                                            <li>
                                                <span>City</span>
                                                <span>Bengaluru</span>
                                            </li>
                                            <li>
                                                <span>State</span>
                                                <span>Karnataka</span>
                                            </li>
                                            <li>
                                                <span>Country</span>
                                                <span>India</span>
                                            </li>
                                            <li>
                                                <span>Pincode</span>
                                                <span>560001</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <div class="accordion-header-alt">
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gallery_one" id="gallery_one">
                                            </div>
                                        </div>
                                        <div>
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#locationAccordion_tab_two" aria-expanded="false" aria-controls="locationAccordion_tab_two">
                                                Secondary Gallery - Bandra
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="locationAccordion_tab_two" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <ul class="personal-info black-txt">
                                            <li>
                                                <span>Sub-Location</span>
                                                <span>Koramangala 4th Block</span>
                                            </li>
                                            <li>
                                                <span>Address</span>
                                                <span>Art Complex, 1, Kumarakrupa Rd, near The Lalit Hotel, Kumara Park East, Seshadri Puram </span>
                                            </li>
                                            <li>
                                                <span>City</span>
                                                <span>Bengaluru</span>
                                            </li>
                                            <li>
                                                <span>State</span>
                                                <span>Karnataka</span>
                                            </li>
                                            <li>
                                                <span>Country</span>
                                                <span>India</span>
                                            </li>
                                            <li>
                                                <span>Pincode</span>
                                                <span>560001</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>
                <section class="section-inner top-border">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="component_radio" id="component_radio">
                        <label class="form-check-label" for="component_radio">
                            Components
                        </label>
                        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#addComponentModal">
                            <i class="fa fa-plus" aria-hidden="true"></i> ADD COMPONENT
                        </button>
                    </div>
                    <article class="edit-wrapper-plain">
                        <div class="accordion accordion-flush custom-accordion" id="components">
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <div class="accordion-header-alt">
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gallery_one" id="gallery_one">
                                            </div>
                                        </div>
                                        <div>
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#components_tab_one" aria-expanded="false" aria-controls="components_tab_one">
                                                Component 1
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="components_tab_one" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <ul class="personal-info black-txt">
                                            <div class="ul-image">
                                                <img src="{{asset('demo/components_one.png')}}" alt="components_one" />
                                                <p>Component 1</p>
                                            </div>
                                            <li>
                                                <span>Asign Object Number</span>
                                                <span>19203</span>
                                            </li>
                                            <li>
                                                <span>Accession Number</span>
                                                <span>1920.31</span>
                                            </li>
                                        </ul>
                                        <ul class="personal-info black-txt">
                                            <div class="ul-title">Medium</div>
                                            <li>
                                                <span>Medium</span>
                                                <span>Oil</span>
                                            </li>
                                            <li>
                                                <span>Surface</span>
                                                <span>Mountboard</span>
                                            </li>
                                            <li>
                                                <span>Technique</span>
                                                <span>Dry Brush</span>
                                            </li>
                                        </ul>
                                        <ul class="personal-info black-txt">
                                            <div class="ul-title">Measurements</div>
                                            <li>
                                                <span>Shape</span>
                                                <span>Rectangle</span>
                                            </li>
                                            <li>
                                                <span>Measurement Type</span>
                                                <span>Centimetre (Cm)</span>
                                            </li>
                                            <li>
                                                <span>Height</span>
                                                <span>52 cm</span>
                                            </li>
                                            <li>
                                                <span>Width</span>
                                                <span>52 cm</span>
                                            </li>
                                            <li>
                                                <span>Depth</span>
                                                <span>52 cm</span>
                                            </li>
                                            <li>
                                                <span>Diameter</span>
                                                <span>52 cm</span>
                                            </li>
                                            <li>
                                                <span>Weight</span>
                                                <span>5 kgs</span>
                                            </li>
                                        </ul>
                                        <ul class="personal-info black-txt">
                                            <div class="ul-title">Signature & Inscriptions</div>
                                            <li>
                                                <span>Signature</span>
                                                <span>Yes</span>
                                            </li>
                                            <li>
                                                <span>Description</span>
                                                <span>Enter</span>
                                            </li>
                                            <li>
                                                <span>Inscriptions</span>
                                                <span>No</span>
                                            </li>
                                        </ul>
                                        <ul class="personal-info black-txt">
                                            <div class="ul-title">Location</div>
                                            <li>
                                                <span>Save Location As</span>
                                                <span>Office Address</span>
                                            </li>
                                            <li>
                                                <span>Sub-Location</span>
                                                <span>Koramangla 4th Block</span>
                                            </li>
                                            <li>
                                                <span>Address</span>
                                                <span>Art Complex, 1, Kumarakrupa Rd, near The Lalit Hotel, Kumara Park East, Seshadri Puram</span>
                                            </li>
                                            <li>
                                                <span>City</span>
                                                <span>Bengaluru</span>
                                            </li>
                                            <li>
                                                <span>State</span>
                                                <span>Karnataka</span>
                                            </li>
                                            <li>
                                                <span>Country</span>
                                                <span>India</span>
                                            </li>
                                            <li>
                                                <span>Pincode</span>
                                                <span>560001</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <div class="accordion-header-alt">
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gallery_one" id="gallery_one">
                                            </div>
                                        </div>
                                        <div>
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#components_tab_two" aria-expanded="false" aria-controls="components_tab_two">
                                                Component 2
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="components_tab_two" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <ul class="personal-info black-txt">
                                            <div class="ul-image">
                                                <img src="{{asset('demo/components_two.png')}}" alt="components_two" />
                                                <p>Component 2</p>
                                            </div>
                                            <li>
                                                <span>Asign Object Number</span>
                                                <span>19203</span>
                                            </li>
                                            <li>
                                                <span>Accession Number</span>
                                                <span>1920.31</span>
                                            </li>
                                        </ul>
                                        <ul class="personal-info black-txt">
                                            <div class="ul-title">Medium</div>
                                            <li>
                                                <span>Medium</span>
                                                <span>Oil</span>
                                            </li>
                                            <li>
                                                <span>Surface</span>
                                                <span>Mountboard</span>
                                            </li>
                                            <li>
                                                <span>Technique</span>
                                                <span>Dry Brush</span>
                                            </li>
                                        </ul>
                                        <ul class="personal-info black-txt">
                                            <div class="ul-title">Measurements</div>
                                            <li>
                                                <span>Shape</span>
                                                <span>Rectangle</span>
                                            </li>
                                            <li>
                                                <span>Measurement Type</span>
                                                <span>Centimetre (Cm)</span>
                                            </li>
                                            <li>
                                                <span>Height</span>
                                                <span>52 cm</span>
                                            </li>
                                            <li>
                                                <span>Width</span>
                                                <span>52 cm</span>
                                            </li>
                                            <li>
                                                <span>Depth</span>
                                                <span>52 cm</span>
                                            </li>
                                            <li>
                                                <span>Diameter</span>
                                                <span>52 cm</span>
                                            </li>
                                            <li>
                                                <span>Weight</span>
                                                <span>5 kgs</span>
                                            </li>
                                        </ul>
                                        <ul class="personal-info black-txt">
                                            <div class="ul-title">Signature & Inscriptions</div>
                                            <li>
                                                <span>Signature</span>
                                                <span>Yes</span>
                                            </li>
                                            <li>
                                                <span>Description</span>
                                                <span>Enter</span>
                                            </li>
                                            <li>
                                                <span>Inscriptions</span>
                                                <span>No</span>
                                            </li>
                                        </ul>
                                        <ul class="personal-info black-txt">
                                            <div class="ul-title">Location</div>
                                            <li>
                                                <span>Save Location As</span>
                                                <span>Office Address</span>
                                            </li>
                                            <li>
                                                <span>Sub-Location</span>
                                                <span>Koramangla 4th Block</span>
                                            </li>
                                            <li>
                                                <span>Address</span>
                                                <span>Art Complex, 1, Kumarakrupa Rd, near The Lalit Hotel, Kumara Park East, Seshadri Puram</span>
                                            </li>
                                            <li>
                                                <span>City</span>
                                                <span>Bengaluru</span>
                                            </li>
                                            <li>
                                                <span>State</span>
                                                <span>Karnataka</span>
                                            </li>
                                            <li>
                                                <span>Country</span>
                                                <span>India</span>
                                            </li>
                                            <li>
                                                <span>Pincode</span>
                                                <span>560001</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>
            </div>
        </div>
    </section>
</div>
@include('components.popups.add_location_popup')
@include('components.popups.add_component_popup')
@include('components.popups.add_image_popup')
@include('components.popups.reject_protectplus')
@include('components.popups.reject_protectplus_reason')
@endsection
@push('scripts')
{{-- https://www.jqueryscript.net/time-clock/Highly-Customizable-jQuery-Datepicker-Plugin-Datepicker.html --}}
{{-- https://www.jqueryscript.net/form/ajax-file-uploader.html --}}
{{-- https://www.jqueryscript.net/other/Responsive-Mobile-friendly-Image-Cropper-With-jQuery-rcrop.html --}}
<script src="{{asset('js/jquery-ui.min.js')}}"></script>
<script src="{{asset('js/select2.min.js')}}"></script>
<script src="{{asset('js/qcTimepicker.min.js')}}"></script>
<script src="{{asset('js/rcrop.min.js')}}"></script>
<script src="{{asset('js/jquery.viewbox.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Upload Configurations
        let ajaxConfig = {
            ajaxRequester: function(config, uploadFile, pCall, sCall, eCall) {
                let progress = 0
                let interval = setInterval(() => {
                    progress += 10;
                    pCall(progress)
                    if (progress >= 100) {
                        clearInterval(interval)
                        const windowURL = window.URL || window.webkitURL;
                        sCall({
                            data: windowURL.createObjectURL(uploadFile.file)
                        })
                        // eCall("")
                    }
                }, 300)
            }
        };

        // Gallery Slider
        $('.artwork_thumbs img').on({
            click: function() {
                let thumbnailURL = $(this).attr('src');

                $('.artwork_preview img').fadeOut(200, function() {
                    $(this).attr('src', thumbnailURL);
                }).fadeIn(200);
            }
        });

        // Select2 dropdown
        $('.asign-request select').each(function() {
            $(this).select2({
                width: 'resolve',
                theme: "material",
                tags: true,
                placeholder: $(this).attr('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            });
        });

        $(".select2Box").each(function(){
            var placeholder = $(this).attr('data-placeholder');
            $(this).select2({
                placeholder: placeholder,
                minimumResultsForSearch: Infinity,
            });
        });

        // Datepicker
        $(".datepicker").datepicker({
            dateFormat: "DD, d M, yy"
        });

        console.log("asnsajsa ===================");
        // Timepicker
        $(".timepicker").each(function(){
            var placeholder = $(this).attr('data-placeholder');
            console.log("asnsajsa", placeholder);
            $(this).qcTimepicker({
                format: 'h:mm a',
                placeholder: "asb sah sa s",
            });
        });

        //Lightbox preview
        var vb = $('.view_box').viewbox({
            setTitle: false,
            margin: 40,
        });
        $('.popup-open-button').click(function() {
            vb.trigger('viewbox.open');
        });
        $('.close-button').click(function() {
            vb.trigger('viewbox.close');
        });

        var bs_modal = $('#addImageModal');
        var cropbox = document.getElementById('rc_img');
        var previewbox = document.getElementById('rc_preview');
        var cropthumb, reader, file;

        $("body").on("change", ".image", function(e) {
            var files = e.target.files;
            var done = function(url) {
                cropbox.src = url;
                previewbox.src = url;
                bs_modal.modal('show');
            };

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        bs_modal.on('shown.bs.modal', function() {
            var $image2 = $('#rc_img'),
                $update = $('#update'),
                inputs = {
                    x : $('#x'),
                    y : $('#y'),
                    width : $('#width'),
                    height : $('#height')
                },
                fill = function(){
                    var values = $image2.rcrop('getValues');
                    for(var coord in inputs){
                       inputs[coord].val(values[coord]);
                    }

                    var srcResized = $image2.rcrop('getDataURL');
                    //var srcResized = $image2.rcrop('getDataURL', 350, 240);
                    $('#rc_preview').attr("src", srcResized); 
                };

                $image2.rcrop({
                    preview : {
                        display : true,
                        //size : [350, 240], //Also: ['100%', '100%'],
                        wrapper : '#rc_preview' // where append preview to
                    }
                });

                $image2.on('rcrop-ready rcrop-change rcrop-changed', fill);
                
                $update.click(function(){
                    $image2.rcrop('resize', inputs.width.val(), inputs.height.val(), inputs.x.val(), inputs.y.val());
                    fill();
                });
        }).on('hidden.bs.modal', function() {

        });

    });
</script>
@endpush