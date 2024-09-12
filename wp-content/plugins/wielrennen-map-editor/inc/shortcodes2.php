<?php

add_shortcode('map_editor2' , 'map_editor_design2');

function map_editor_design2(){ ?>
    <section class="load">
        <p>Loading Wielrennen Poster</p>
    </section>
    <section class="unsupported load">
        <p>Loading Wielrennen Poster</p>
    </section>
    <aside class="option">
        <section class="stage activity" data-view-source="activity">
            <div class="scroll">
                <section class="step intro" data-view-source="intro">
                    <header>
                        <div class="title">
                            <h2>Add Your Activities</h2>
                        </div>
                    </header>
                    <div class="content">
                        <div class="transfer">
                            <div class="item strava">
                                <div class="connect">
                                    <a class="button large" data-action-target="activity_strava_authorize">Connect with</a>
                                    <h6>Connect to your Strava account and add your activities</h6>
                                </div>
                                <div class="search">
                                    <a class="button large" data-action-target="activity_strava_explore">Search</a>
                                    <h6>Explore your Strava activities</h6>
                                </div>
                            </div>
                            <div class="item upload">
                                <a class="button large tertiary">
                                    <input type="file" accept=".gpx" multiple="">
                                    Upload GPX Activities
                                </a>
                                <h6>Not on Strava? Upload a GPX.</h6>
                            </div>
                        </div>
                        <div class="question">
                            <h3>FAQs</h3>
                            <div class="item">
                                <p>Not sure what a GPX is or how to get one? Read our handy guide <a href="">here</a> so you can get designing.</p>
                            </div>
                            <div class="item">
                                <p>Want to make a map for someone else? <a href="">Read our guide</a> on how to retrieve or recreate, activity data.</p>
                            </div>
                            <div class="item">
                                <p>Not got your ride recorded? Re-create it in a tool like <a href="https://plotaroute.com" target="_blank">plotaroute.com</a> and download the GPX.</p>
                            </div>
                            <div class="item">
                                <p>Anything else? Use our <a href="<?php echo site_url(); ?>/contact-us">contact form</a> and I will get back to you as soon as possible.</p>
                            </div>
                        </div>
                    </div>
                    <footer></footer>
                </section>
                <section class="step strava" data-view-source="strava">
                    <header>
                        <div class="title">
                            <a class="previous" data-view-target="activity:intro"></a>
                            <h2>Your Strava Rides</h2>
                        </div>
                    </header>
                    <div class="content">
                        <div class="field search">
                            <input type="text" id="search" name="search" placeholder="Search for a Strava activity...">
                        </div>
                        <section class="list">
                            <div class="item clone">
                                <div class="detail">
                                    <h4></h4>
                                    <div class="metadata">
                                        <span class="time"></span>
                                        <span class="distance"></span>
                                        <span class="duration"></span>
                                    </div>
                                </div>
                                <a class="toggle" data-action-target="activity_strava_toggle"></a>
                            </div>
                        </section>
                    </div>
                    <footer>
                        <aside class="note info">
                            <h4>Can’t find your ride?</h4>
                            <p>Sadly Strava limits the amount of rides we can get from their database. Try our <a data-view-target="activity:intro">GPX uploader</a> instead.</p>
                        </aside>
                        <div class="action">
                            <a class="button large primary design" data-view-target="design">Next: Design</a>
                        </div>
                    </footer>
                </section>
                <section class="step inventory" data-view-source="inventory">
                    <header>
                        <div class="title">
                            <h2>Imported Rides</h2>
                        </div>
                    </header>
                    <div class="content">
                        <section class="list position">
                            <div class="item clone">
                                <div class="picture"></div>
                                <div class="detail">
                                    <h4></h4>
                                    <div class="metadata">
                                        <span class="time"></span>
                                        <span class="distance"></span>
                                        <span class="duration"></span>
                                    </div>
                                </div>
                                <a class="delete"></a>
                            </div>
                        </section>
                        <div class="transfer">
                            <div class="item strava">
                                <div class="connect">
                                    <a class="button large" data-action-target="activity_strava_authorize">Connect with</a>
                                    <h6>Connect to your Strava account and add your activities</h6>
                                </div>
                                <div class="search">
                                    <a class="button large" data-action-target="activity_strava_explore">Search</a>
                                    <h6>Explore your Strava activities</h6>
                                </div>
                            </div>
                            <div class="item upload">
                                <a class="button large tertiary">
                                    <input type="file" accept=".gpx" multiple="">
                                    Upload GPX Activities
                                </a>
                                <h6>Not on Strava? Upload a GPX.</h6>
                            </div>
                        </div>
                    </div>
                    <footer>
                        <div class="action">
                            <a class="button large primary design" data-view-target="design">Next: Design</a>
                        </div>
                    </footer>
                </section>
            </div>
        </section>
        <section class="stage design" data-view-source="design">
            <div class="scroll">
                <section class="step design display">
                    <header>
                        <div class="title">
                            <a class="previous" data-view-target="activity:inventory"></a>
                            <h2>Design Your Map</h2>
                        </div>
                        <a>Watch introduction video</a>
                    </header>
                    <div class="content">

                        <!-- style -->
                        <div class="group style">
                            <h3>Color Scheme</h3>
                            <div class="scheme" data-design-key="poster_style">
                                <a class="item grey_light" data-design-value="grey_light">
                                    <div class="picture"></div>
                                    <h6>Light Grey</h6>
                                </a>
                                <a class="item orange" data-design-value="orange">
                                    <div class="picture"></div>
                                    <h6>Orange</h6>
                                </a>
                                <a class="item grey_dark" data-design-value="grey_dark">
                                    <div class="picture"></div>
                                    <h6>Dark Grey</h6>
                                </a>
                                <a class="item blue" data-design-value="blue">
                                    <div class="picture"></div>
                                    <h6>Blue</h6>
                                </a>
                                <a class="item outdoor" data-design-value="outdoor">
                                    <div class="picture"></div>
                                    <h6>Outdoors</h6>
                                </a>
                                <a class="item pastel" data-design-value="pastel">
                                    <div class="picture"></div>
                                    <h6>Pastel</h6>
                                </a>
                                <a class="item spring" data-design-value="spring">
                                    <div class="picture"></div>
                                    <h6>Spring</h6>
                                </a>
                                <a class="item black_white" data-design-value="black_white">
                                    <div class="picture"></div>
                                    <h6>Black &amp; White</h6>
                                </a>
                            </div>
                        </div>

                        <!-- orientation -->
                        <div class="group orientation">
                            <h3>Layout</h3>
                            <div class="block" data-design-key="paper_orientation">
                                <a class="item portrait" data-design-value="portrait">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Portrait</h6>
                                    </div>
                                </a>
                                <a class="item landscape" data-design-value="landscape">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Landscape</h6>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- size -->
                        <div class="group size">
                            <h3>Size</h3>  
                            <div class="block" data-design-key="paper_size">
                                <a class="item" data-design-value="a3">
                                    <div>
                                        <h6>A3</h6>
                                        <div class="metadata">
                                            <span>21 x 29.7 cm</span>
                                            <span>11.7 x 16.5 in</span>
                                        </div>
                                    </div>
                                </a>
                                <a class="item" data-design-value="a2">
                                    <div>
                                        <h6>A2</h6>
                                        <div class="metadata">
                                            <span>42 x 59.4 cm</span>
                                            <span>16.5 x 23.4 in</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- outline -->
                        <div class="group outline">
                            <h3>Outline</h3>
                            <div class="block" data-design-key="outline_type">
                                <a class="item classic" data-design-value="classic">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Classic</h6>
                                    </div>
                                </a>
                                <a class="item circle" data-design-value="circle">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Circle</h6>
                                    </div>
                                </a>
                                <a class="item square" data-design-value="square">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Square</h6>
                                    </div>
                                </a>
                                <a class="item none" data-design-value="none">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>None</h6>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- overlay -->
                        <div class="group overlay">
                            <h3>Gradient Overlay</h3>
                            <div class="block" data-design-key="overlay_type">
                                <a class="item radial" data-design-value="radial">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Radial</h6>
                                    </div>
                                </a>
                                <a class="item linear" data-design-value="linear">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Vertical</h6>
                                    </div>
                                </a>
                                <a class="item none" data-design-value="none">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>None</h6>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- text -->
                        <div class="group text">
                            <h3>Titles</h3>
                            <div class="form" data-design-key="text">
                                <div class="field text">
                                    <input type="text" id="text[headline]" name="text[headline]" placeholder="Add headline">
                                </div>
                                <div class="field text">
                                    <input type="text" id="text[subtitle]" name="text[subtitle]" placeholder="Add subtitle">
                                </div>
                                <div class="field text">
                                    <input type="text" id="text[footnote]" name="text[footnote]" placeholder="Add footnote">
                                </div>
                                <div class="field text">
                                    <input type="text" id="text[metadata]" name="text[metadata]" placeholder="Add metadata">
                                    <div class="note">
                                        <strong>The text is editable</strong> and the imported data isn’t always perfect — please review it.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- label -->
                        <div class="group label">
                            <h3>Map Markers</h3>
                            <p>
                                Click the map add a marker.
                                <a href="">Watch our quick video on how this works</a>    
                            </p>
                            <div class="item clone">
                                <div class="field text" data-design-key="label_text">
                                    <input type="text" id="" name="" value="">
                                </div>
                                <div class="block" data-design-key="label_anchor">
                                    <a class="item anchor_left" data-design-value="left">
                                        <div>
                                            <div class="icon"></div>
                                            <h6>Dot on left</h6>
                                        </div>
                                    </a>
                                    <a class="item anchor_bottom" data-design-value="bottom">
                                        <div>
                                            <div class="icon"></div>
                                            <h6>Centered</h6>
                                        </div>
                                    </a>
                                    <a class="item anchor_right" data-design-value="right">
                                        <div>
                                            <div class="icon"></div>
                                            <h6>Dot on right</h6>
                                        </div>
                                    </a>
                                    <a class="item delete" data-design-value="delete">
                                        <div>
                                            <div class="icon"></div>
                                            <h6>Delete</h6>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- line width -->
                        <div class="group line_width">
                            <h3>Line Thickness</h3>
                            <div class="block" data-design-key="activity_line_width">
                                <a class="item width_1" data-design-value="1"></a>
                                <a class="item width_2" data-design-value="2"></a>
                                <a class="item width_3" data-design-value="3"></a>
                                <a class="item width_4" data-design-value="4"></a>
                                <a class="item width_5" data-design-value="5"></a>
                            </div>
                        </div>

                        <!-- font family -->
                        <div class="group font_family">
                            <h3>Font Family</h3>
                            <div class="inline" data-design-key="font_family">
                                <a class="item" data-design-value="circular">Circular</a>
                                <a class="item" data-design-value="effra">Effra</a>
                                <a class="item" data-design-value="source">Source Sans</a>
                                <a class="item" data-design-value="montserrat">Montserrat</a>
                                <a class="item" data-design-value="roboto">Roboto Slab</a>
                                <a class="item" data-design-value="literata">Literata</a>
                                <a class="item" data-design-value="playfair">Playfair</a>
                                <a class="item" data-design-value="redrose">Red Rose</a>
                            </div>
                        </div>

                        <!-- font size -->
                        <div class="group font_size">
                            <h3>Font Size</h3>
                            <div class="inline" data-design-key="font_size">
                                <a class="item" data-design-value="small">Small</a>
                                <a class="item" data-design-value="medium">Medium</a>
                                <a class="item" data-design-value="large">Large</a>
                                <a class="item" data-design-value="extra">Extra</a>
                            </div>
                        </div>

                        <!-- elevation profile -->
                        <div class="group elevation_enable">
                            <h3>Elevation Profile</h3>
                            <div class="inline" data-design-key="elevation_enable">
                                <a class="item" data-design-value="true">Yes</a>
                                <a class="item" data-design-value="false">No</a>
                            </div>
                            <p>
                                Make sure your rides are in the right order.
                                <a data-view-target="activity:inventory">If they need adjusting go back to change the order</a>
                            </p>
                        </div>

                        <!-- elevation multiply -->
                        <div class="group elevation_multiply">
                            <h3>Elevation Multipler</h3>
                            <div class="block" data-design-key="elevation_multiply">
                                <a class="item small" data-design-value="small">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Small</h6>
                                    </div>
                                </a>
                                <a class="item medium" data-design-value="medium">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Medium</h6>
                                    </div>
                                </a>
                                <a class="item large" data-design-value="large">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Large</h6>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- point finish -->
                        <div class="group point_finish">
                            <h3>Activity End Points</h3>
                            <div class="block" data-design-key="activity_point_finish">
                                <a class="item positive" data-design-value="true">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Yes</h6>
                                    </div>
                                </a>
                                <a class="item negative" data-design-value="false">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>No</h6>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- point track -->
                        <div class="group point_activity">
                            <h3>Activity Start &amp; Stop Point</h3>
                            <div class="block" data-design-key="activity_point_activity">
                                <a class="item positive" data-design-value="true">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>Yes</h6>
                                    </div>
                                </a>
                                <a class="item negative" data-design-value="false">
                                    <div>
                                        <div class="icon"></div>
                                        <h6>No</h6>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>
                    <footer>
                        <div class="prompt display">Scroll down for more options</div>
                        <div class="order">
                            <span class="size">A3</span>
                            <span class="price">00.00</span>
                            <span class="shipping">Includes Worldwide Delivery</span>
                        </div>
                        <div class="action">
                            <a class="button large tertiary editor" data-action-target="option_toggle">Editor</a>
                            <a class="button large primary review" data-view-target="review">Next: Review</a>
                        </div>
                    </footer>
                </section>
            </div>
        </section>
        <section class="stage review" data-view-source="review">
            <div class="scroll">
                <section class="step review display">
                    <header>
                        <div class="title">
                            <a class="previous" data-action-target="review_modify"></a>
                            <h2>Review &amp; Approve</h2>
                        </div>
                    </header>
                    <div class="content">
                        <aside class="note info">
                            <h4>Please review your design</h4>
                            <p>We don’t take responsibility for incorrectly entered information and due to our quick turnaround we are unable to amend the artwork after this point.</p>
                        </aside>
                        <ul>
                            <li><span class="emoji">📦</span> Free worldwide shipping on prints</li>
                            <li><span class="emoji">🚴‍♂️</span> Delivered in 3–7 days on average</li>
                            <li><span class="emoji">🌱</span> 1 tree planted for every order</li>
                            <li><span class="emoji">⭐️</span> Average rating 4.92</li>
                            <li><span class="emoji">💯</span> 100% money back guarantee</li>
                        </ul>
                        <div class="text">
                            <h4>Any questions</h4>
                            <p>Get in <a href="<?php echo site_url(); ?>/contact-us" target="_blank">touch</a> </p>
                        </div>
                    </div>
                    <footer>
                        <div class="field toggle">
                            <input type="checkbox" id="confirm" name="confirm">
                            <label for="confirm">I have reviewed the file and am happy for it to go to print</label>
                        </div>
                        <div class="action">
                            <a class="button large tertiary edit" data-action-target="review_modify">Edit</a>
                            <a class="button large primary checkout disabled" data-action-target="review_checkout">Checkout</a>
                        </div>
                    </footer>
                </section>
            </div>
        </section>
    </aside>
    <main>

        <!-- preview -->
        <section class="preview">
            <section class="poster">
                <section class="layer map">

                    <!-- basemap -->
                    <!-- iframe -->
                    <iframe class="basemap" src="https://mapeditor.arhamsoft.info/basemap/?_=<?php echo rand(); ?>" frameborder="0"></iframe>

                </section>
                <section class="layer overlay"></section>
                <section class="layer activity">

                    <!-- point -->
                    <div class="point clone"></div>

                    <!-- basemap -->
                    <!-- iframe -->
                    <iframe class="basemap" src="https://mapeditor.arhamsoft.info/basemap/?_=<?php echo rand(); ?>" frameborder="0"></iframe>

                </section>
                <section class="layer label">

                    <!-- marker -->
                    <div class="marker clone">
                        <div class="label">
                            <div class="anchor"></div>
                            <div class="text"></div>
                        </div>
                    </div>

                    <!-- basemap -->
                    <!-- iframe -->
                    <iframe class="basemap" src="https://mapeditor.arhamsoft.info/basemap/?_=<?php echo rand(); ?>" frameborder="0"></iframe>

                </section>
                <section class="layer outline"></section>
                <section class="layer elevation"></section>
                <section class="layer text">
                    <div class="headline"></div>
                    <div class="subtitle"></div>
                    <div class="footnote"></div>
                    <div class="metadata"></div>
                </section>
            </section>
        </section>

        <!-- control -->
        <section class="control">
            <a class="pill center" data-action-target="activity_bound"></a>
            <a class="pill scale" data-action-target="poster_scale_toggle"></a>
            <div class="zoom">
                <a class="positive" data-zoom-action="positive"></a>
                <a class="negative" data-zoom-action="negative"></a>
            </div>
        </section>

        <!-- credit -->
        <section class="credit"></section>

    </main>

    <aside class="alert">
        <div class="item clone">
            <h6></h6>
            <p></p>
        </div>
    </aside>



<?php }