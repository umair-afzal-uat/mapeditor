<?php

add_shortcode('map_editor' , 'map_editor_design');

function map_editor_design(){ ?>
<section class="map-editor-section">
	<div class="wrapper">
		<div class="row">
			<div class="col-md-4">
				<div class="sidebar">
					
						<!-- STEP SECTION -->
						<section class="step intro display" data-view-source="intro">
							<header>
								<div class="title">
								    <h2>Add Your Activities</h2>
								</div>
							</header>
						    <div class="scroll">
							    <div class="content">
							        <div class="transfer">
									    <div class="item strava">
									        <div class="connect">
									            <a class="button large activity_strava_authorize" data-action-target="activity_strava_authorize">Connect with strava</a>
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
									<div class="title text-center mt-4">
							            <h2>FAQs</h2>
							        </div>
							        <div class="question">
							            <div class="item">
							                <p>Not sure what a GPX is or how to get one? Read our handy guide <a href="">here</a> so you can get designing.</p>
							            </div>
							            <div class="item">
							                <p>Want to make a map for someone else? <a href="">Read our guide</a> on how to retrieve or recreate, activity data.</p>
							            </div>
							            <div class="item">
							                <p>Not got your ride recorded? Re-create it in a tool like <a href="" target="_blank">plotaroute.com</a> and download the GPX.</p>
							            </div>
							            <div class="item">
							                   <p>Anything else? Use our <a href="/contact-us">contact form</a> and I will get back to you as soon as possible.</p>
							            </div>
							        </div>
						    	</div>
							</div>
						</section>

						<!-- Starva SECTION -->
						<section class="step strava" data-view-source="strava">
						    <header>
						        <div class="title">
						            <!--<a class="previous"></a>-->
						            <h2>Your Strava Rides</h2>
						        </div>
						    </header>
						    <div class="scroll">
							    <div class="content">
							        <div class="field search">
							            <input type="text" id="search" name="search" placeholder="Search for a Strava activity...">
							        </div>
							        <section class="list">
							            <div class="list-item clone ">
							                <div class="detail">
							                	<h4></h4>
							                    <div class="metadata">
							                        <span class="time"></span>
							                        <span class="distance"></span>
							                        <span class="duration"></span>
							                    </div>
							                </div>
							                <a class="toggle single_activity"></a>
							            </div>
							           
										
							        </section>
							    </div>
						    </div>
						    <footer>
						        <div class="content">
						            <h4>Can’t find your ride?</h4>
						            <p>Sadly Strava limits the amount of rides we can get from their database. Try our <a data-view-target="activity:intro">GPX uploader</a> instead.</p>
						        </div>
						        <div class="action">
						            <a class="button large primary design" data-view-target="design">Next: Design</a>
						        </div>
						    </footer>
						</section>

						<!-- Starva SECTION -->
						<section class="step stage design " data-view-source="strava">
						    <header>
						        <div class="title">
						            <!--<a class="previous"></a>-->
						            <h2>Design Your Map</h2>
						        </div>
						        <a>Watch introduction video</a>
						    </header>
						    <div class="scroll">
							    <div class="content">
							       <!-- STYLE -->
							       <div class="group style">
									    <h3>Color Scheme</h3>
									    <div class="scheme" data-design-key="poster_style">
									        <a class="item grey_light active" data-design-value="grey_light">
									            <div class="picture">
									            	<img class="img-fluid" src="/wp-content/uploads/2021/09/grey_light.jpg" alt="">
									            </div>
									            <h6>Light Grey</h6>
									        </a>
									        <a class="item orange" data-design-value="orange">
									            <div class="picture">
									            	<img class="img-fluid" src="/wp-content/uploads/2021/09/orange.jpg" alt="">
									            </div>
									            <h6>Orange</h6>
									        </a>
									        <a class="item grey_dark" data-design-value="grey_dark">
									            <div class="picture">
									            	<img class="img-fluid" src="/wp-content/uploads/2021/09/grey_dark.jpg" alt="">
									            </div>
									            <h6>Dark Grey</h6>
									        </a>
									        <a class="item blue" data-design-value="blue">
									            <div class="picture">
									            	<img class="img-fluid" src="/wp-content/uploads/2021/09/blue.jpg" alt="">
									            </div>
									            <h6>Blue</h6>
									        </a>
									        <a class="item outdoor" data-design-value="outdoor">
									            <div class="picture">
									            	<img class="img-fluid" src="/wp-content/uploads/2021/09/pastel.jpg" alt="">
									            </div>
									            <h6>Outdoors</h6>
									        </a>
									        <a class="item pastel" data-design-value="pastel">
									            <div class="picture">
									            	<img class="img-fluid" src="/wp-content/uploads/2021/09/grey_light-1.jpg" alt="">
									            </div>
									            <h6>Pastel</h6>
									        </a>
									        <a class="item spring" data-design-value="spring">
									            <div class="picture">
									            	<img class="img-fluid" src="/wp-content/uploads/2021/09/spring.jpg" alt="">
									            </div>
									            <h6>Spring</h6>
									        </a>
									        <a class="item black_white" data-design-value="black_white">
									            <div class="picture">
									            	<img class="img-fluid" src="/wp-content/uploads/2021/09/black_white.jpg" alt="">
									            </div>
									            <h6>Black &amp; White</h6>
									        </a>
									    </div>
								   </div>

								   <!-- Orientation -->
								  	<div class="row mt-3">
									    <div class="col-sm-6 group orientation">
										    <h3>Layout</h3>
										    <div class="block" data-design-key="paper_orientation">
										        <a class="item portrait" data-design-value="portrait">
										            <div>
										                <div class="icon"></div>
										                <h6>Portrait</h6>
										            </div>
										        </a>
										        <a class="item landscape active" data-design-value="landscape">
										            <div>
										                <div class="icon"></div>
										                <h6>Landscape</h6>
										            </div>
										        </a>
										    </div>
										</div>
										<div class="col-sm-6 group size">
										    <h3>Size</h3>
										    <div class="block" data-design-key="paper_orientation">
										        <a class="item portrait" data-design-value="portrait">
										            <div>
								                        <h6>A3</h6>
								                        <div class="metadata">
								                            <span>21 x 29.7 cm</span>
								                            <span>11.7 x 16.5 in</span>
								                        </div>
								                    </div>
										        </a>
										        <a class="item landscape active" data-design-value="landscape">
										            <div>
								                        <h6>A3</h6>
								                        <div class="metadata">
								                            <span>21 x 29.7 cm</span>
								                            <span>11.7 x 16.5 in</span>
								                        </div>
								                    </div>
										        </a>
										    </div>
										</div>
								    </div>
								   <!-- Outline -->
								   <div class="group outline">
									    <h3>Outline</h3>
									    <div class="block" data-design-key="poster_style">
									        <a class="item active classic" data-design-value="classic">
									            <h6>Classic</h6>
									            <div class="outline-icon icon">
									            	<span></span>
									            </div>
									        </a>
									        <a class="item Circle" data-design-value="circle">
									            <h6>Circle</h6>
									            <div class="circle-icon icon">
									            	<span></span>
									            </div>
									        </a>
									        <a class="item square" data-design-value="orange">
									            <h6>Square</h6>
									            <div class="square-icon icon">
									            	<span></span>
									            </div>
									        </a>
									        <a class="item none" data-design-value="orange">
									            <h6>None</h6>
									            <div class="none-icon icon">
									            	<span></span>
									            </div>
									        </a>
									        
									    </div>
								   </div>
								   <!-- Gradient Overlay -->
								   <div class="group gradient">
									    <h3>Gradient Overlay</h3>
									    <div class="block" data-design-key="poster_style">
									        <a class="item active Radial" data-design-value="classic">
									            <h6>Radial</h6>
									            <div class="radial-icon icon">
									            	<span></span>
									            </div>
									        </a>
									        <a class="item Vertical" data-design-value="circle">
									            <h6>Vertical</h6>
									            <div class="vertical-icon icon">
									            	<span></span>
									            </div>
									        </a>
									        <a class="item square" data-design-value="orange">
									            <h6>None</h6>
									            <div class="none-icon icon">
									            	<span></span>
									            </div>
									        </a>
									    </div>
								   </div>
								   <!-- group Text -->
								   <div class="group text">
									    <h3>Text</h3>
									    <div class="row">
									        <div class="col-12 form">
									        	<div class="form-group">
									        		<input class="form-control" type="text" id="text[headline]" name="text[headline]" placeholder="Add headline">
									        	</div>
									        	<div class="form-group">
									        		<input class="form-control" type="text" id="text[subtitle]" name="text[subtitle]" placeholder="Add subtitle">
									        	</div>
									        	<div class="form-group">
									        		<input class="form-control" type="text" id="text[footnote]" name="text[footnote]" placeholder="Add footnote">
									        	</div>
									        	<div class="form-group">
									        		<input class="form-control" type="text" id="text[metadata]" name="text[metadata]" placeholder="Add metadata">
									        	</div>
									        	<div class="form-group">
									        		<div class="note">
                        								<strong>The text is editable</strong> and the imported data isn’t always perfect — please review it.
                    								</div>
									        	</div>
									        </div>
									    </div>
								   </div>

								   <!--Label-->
								   <div class="group label">
							            <h3>Map Markers</h3>
							            <p>
							                Click the map add a marker.
							                <a href="">Watch our quick video on how this works</a>    
							            </p>
							            <div class="item clone">
							                <div class="form-group" data-design-key="label_text">
							                    <input class="form-control" type="text" id="" name="" value="">
							                </div>
							                <div class="block" data-design-key="label_anchor">
							                    <a class="item anchor_left" data-design-value="left">
						                            <div class="svg-icon">
						                            	<svg viewBox="0 0 31.5 13" xmlns="http://www.w3.org/2000/svg">
														    <path d="m0 6.39a4 4 0 1 0 8 0 4 4 0 1 0 -8 0"/>
														    <path d="m16.5 0h15v1h-15z"/><path d="m16.5 4h13v1h-13z"/>
														    <path d="m16.5 8h15v1h-15z"/><path d="m16.5 12h8v1h-8z"/>
														</svg>
						                            </div>
						                            <h6>Dot on left</h6>
							                    </a>
							                    <a class="item anchor_bottom active" data-design-value="bottom">
						                            <div class="svg-icon">
						                            	<svg  viewBox="0 0 15 13" xmlns="http://www.w3.org/2000/svg">
														    <path d="m0 0h15v1h-15z"/><path d="m2 4h11v1h-11z"/>
														    <path d="m0 8h15v1h-15z"/><path d="m4 12h7v1h-7z"/>
														</svg>
						                            </div>
						                            <h6>Centered</h6>
							                    </a>
							                    <a class="item anchor_right" data-design-value="right">
						                            <div class="svg-icon">
						                            	<svg  viewBox="0 0 29.5 13" xmlns="http://www.w3.org/2000/svg">
														    <path d="m0 0h15v1h-15z"/><path d="m2 4h13v1h-13z"/>
														    <path d="m0 8h15v1h-15z"/><path d="m6 12h9v1h-9z"/>
														    <path d="m29.42 6.81a4.14 4.14 0 0 0 0-1.62 4 4 0 1 0 -4.17 4.81h.25a3.75 3.75 0 0 0 .5 0 4 4 0 0 0 3.42-3.19z"/>
														</svg>
						                            </div>
						                            <h6>Dot on right</h6>
							                    </a>
							                    <a class="item delete" data-design-value="delete">
						                            <div class="svg-icon">
						                            	<svg viewBox="0 0 15.63 19.25" xmlns="http://www.w3.org/2000/svg">
															<path d="m14.14 2.41h-3.31v-.61a1.81 1.81 0 0 0 -1.83-1.8h-2.38a1.8 1.8 0 0 0 -1.8 1.8v.6h-3.32a1.51 1.51 0 0 0 -1.5 1.51v2.09a.6.6 0 0 0 .6.6h.33l.52 10.91a1.8 1.8 0 0 0 1.8 1.72h9.13a1.8 1.8 0 0 0 1.8-1.72l.52-10.89h.3a.6.6 0 0 0 .6-.6v-2.11a1.5 1.5 0 0 0 -1.46-1.5zm-8.14-.6a.59.59 0 0 1 .6-.6h2.4a.6.6 0 0 1 .6.6v.6h-3.6zm-4.8 2.1a.29.29 0 0 1 .3-.3h12.64a.3.3 0 0 1 .3.3v1.5h-13.24zm11.8 13.57a.6.6 0 0 1 -.6.57h-9.15a.6.6 0 0 1 -.6-.57l-.51-10.86h11.37z"/>
															<path d="m7.82 16.85a.6.6 0 0 0 .6-.6v-7.83a.6.6 0 0 0 -1.2 0v7.82a.61.61 0 0 0 .6.61z"/>
															<path d="m10.83 16.85a.6.6 0 0 0 .6-.6v-7.83a.6.6 0 0 0 -1.2 0v7.82a.61.61 0 0 0 .59.61z"/>
															<path d="m4.81 16.85a.6.6 0 0 0 .6-.6v-7.83a.6.6 0 0 0 -1.2 0v7.82a.62.62 0 0 0 .6.61z"/>
														</svg>
						                            </div>
						                            <h6>Delete</h6>
							                    </a>
							                </div>
							            </div>
							       </div>

							       <!-- Line Width -->
							       <div class="group line_width">
									    <h3>Line Thickness</h3>
									    <div class="block" data-design-key="activity_line_width">
									        <a class="item width_1" data-design-value="1">
									        	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 57.37 56.69">
									        		<path d="M56.87,56.69C40.23,56.69,32.5,41.28,25,26.38,18.44,13.27,12.2.92.5,1h0a.5.5,0,1,1,0-1H.67C12.9,0,19.22,12.6,25.92,25.93c7.34,14.63,14.93,29.76,30.95,29.76a.5.5,0,0,1,.5.5A.5.5,0,0,1,56.87,56.69Z"/>
									        	</svg>

									        </a>
									        <a class="item width_2" data-design-value="2">
									        	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 58.37 57.69">
									        		<path d="M57.37,57.69c-16.94,0-24.75-15.55-32.29-30.58C18.6,14.2,12.47,2,1.16,2H1A1,1,0,0,1,1,0h.18C13.71,0,20.1,12.73,26.86,26.21c7.28,14.49,14.8,29.48,30.51,29.48a1,1,0,1,1,0,2Z"/>
									        	</svg>
									        </a>
									        <a class="item width_3 active" data-design-value="3">
									        	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 59.37 58.69">
									        		<path d="M57.87,58.69c-17.25,0-25.48-16.4-32.74-30.86C18.69,15,12.61,2.86,1.51,3A1.51,1.51,0,0,1,0,1.52,1.51,1.51,0,0,1,1.49,0c13-.13,19.45,12.8,26.32,26.49C35,40.84,42.47,55.69,57.87,55.69a1.5,1.5,0,1,1,0,3Z">
									        	</svg>
									        </a>
									        <a class="item width_4" data-design-value="4">
									        	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60.37 59.69">
									        		<path d="M58.37,59.69c-17.56,0-25.86-16.54-33.19-31.13C18.84,15.93,12.86,4,2.16,4H2A2,2,0,0,1,2,0h.19c13.15,0,20,13.6,26.59,26.76C35.9,41,43.28,55.69,58.37,55.69a2,2,0,1,1,0,4Z"/>
									        	</svg>
									        </a>
									        <a class="item width_5" data-design-value="5">
									        	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 61.37 60.69">
									        		<path d="M58.87,60.69C41,60.69,32.63,44,25.23,29.28,19,16.8,13.05,5,2.66,5H2.5a2.5,2.5,0,0,1,0-5h.19C16.13,0,23,13.74,29.7,27c7.07,14.08,14.39,28.65,29.17,28.65a2.5,2.5,0,1,1,0,5Z" />
									        	</svg>
									        </a>
									    </div>
								   </div>

								   <!-- Font Family -->
								   <div class="group font_family">
									    <h3>Font Family</h3>
									    <div class="inline" data-design-key="font_family">
									        <a class="item active" data-design-value="circular">Circular</a>
									        <a class="item" data-design-value="effra">Effra</a>
									        <a class="item" data-design-value="source">Source Sans</a>
									        <a class="item" data-design-value="montserrat">Montserrat</a>
									        <a class="item" data-design-value="roboto">Roboto Slab</a>
									        <a class="item" data-design-value="literata">Literata</a>
									        <a class="item" data-design-value="playfair">Playfair</a>
									        <a class="item" data-design-value="redrose">Red Rose</a>
									    </div>
									</div>

									<!-- Font Size -->
									<div class="group font_size">
							            <h3>Font Size</h3>
							            <div class="inline" data-design-key="font_size">
							                <a class="item" data-design-value="small">Small</a>
							                <a class="item active" data-design-value="medium">Medium</a>
							                <a class="item" data-design-value="large">Large</a>
							                <a class="item" data-design-value="extra">Extra</a>
							            </div>
							        </div>

							        <!-- Elevation -->
							        <div class="group elevation_enable">
							            <h3>Elevation Profile</h3>
							            <div class="inline" data-design-key="elevation_enable">
							                <a class="item active" data-design-value="true">Yes</a>
							                <a class="item" data-design-value="false">No</a>
							            </div>
							            <p>
							                Make sure your rides are in the right order.
							                <a data-view-target="activity:inventory">If they need adjusting go back to change the order</a>
							            </p>
							        </div>
							        <!-- Elevation Multipler -->
							        <div class="group elevation_multiply display">
									    <h3>Elevation Multipler</h3>
									    <div class="block" data-design-key="elevation_multiply">
									        <a class="item small active" data-design-value="small">
								                <div class="shapes">
								                	<span></span>
								                </div>
								                <h6>Small</h6>
									        </a>
									        <a class="item medium" data-design-value="medium">
								                <div class="shapes">
								                	<span></span>
								                </div>
								                <h6>Medium</h6>
									        </a>
									        <a class="item large" data-design-value="large">
								                <div class="shapes">
								                	<span></span>
								                </div>
								                <h6>Large</h6>
									        </a>
									    </div>
									</div>

									<!-- Finish Point -->
									<div class="group point_finish">
									    <h3>Activity End Points</h3>
									    <div class="block" data-design-key="activity_point_finish">
									        <a class="item positive" data-design-value="true">
								                <div class="svg-icon">
								                	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 11">
								                		<path d="M64.5,0a5.49,5.49,0,0,0-5.4,4.5H39.9a5.49,5.49,0,0,0-10.8,0H10.9a5.5,5.5,0,1,0,0,2H29.1a5.49,5.49,0,0,0,10.8,0H59.1A5.5,5.5,0,1,0,64.5,0Z"/>
								                	</svg>
								                </div>
								                <h6>Yes</h6>
									        </a>
									        <a class="item negative active" data-design-value="false">
									            <div class="svg-icon">
								                	<svg viewBox="0 0 61 2" xmlns="http://www.w3.org/2000/svg"><path d="m31 0h-1-30v2h30 1 30v-2z"/></svg>
								                	<h6>No</h6>
								                </div>
									        </a>
									    </div>
									</div>
									<!-- Activity Points -->
									<div class="group point_activity">
							            <h3>Activity Start &amp; Stop Point</h3>
							            <div class="block" data-design-key="activity_point_activity">
							                <a class="item positive" data-design-value="true">
							                    <div class="svg-icon">
								                	<svg viewBox="0 0 70 13" xmlns="http://www.w3.org/2000/svg"><path d="m59 1v4.5h-23.5-1-25.19l-9.31-5.5v13l9.31-5.5h25.19 1 23.5v4.5h11v-11z"/></svg>
								                	<h6>Yes</h6>
								                </div>
							                </a>
							                <a class="item negative active" data-design-value="false">
							                    <div class="svg-icon">
								                	<svg viewBox="0 0 61 2" xmlns="http://www.w3.org/2000/svg"><path d="m31 0h-1-30v2h30 1 30v-2z"/></svg>
								                	<h6>No</h6>
								                </div>
							                </a>
							            </div>
							        </div>


							    </div>
						    </div>
						    <footer>
						        <div class="content">
						            <div class="order">
							            <span class="size">A3</span>
							            <span class="price">00.00</span>
							            <span class="shipping">Includes Worldwide Delivery</span>
							        </div>
						        </div>
						        <div class="action">
						            <a class="button large primary design" data-view-target="design">Next: Review</a>
						        </div>
						    </footer>
						</section>

						<!-- Review & Prove -->
						<section class="step review " data-view-source="review">
							<header>
								<div class="title">
								    <h2>Review & Approve</h2>
								</div>
							</header>
						    <div class="scroll">
							    <div class="content">
							        <div class="bg-white mb-4">
							        	<h4>Please review your design</h4>
							        	<p class="mb-0">We don’t take responsibility for incorrectly entered information and due to our quick turnaround we are unable to amend the artwork after this point.</p>
							        </div>
							        <ul>
							            <li><span class="emoji">📦</span> Free worldwide shipping on prints</li>
							            <li><span class="emoji">🚴&zwj;♂️</span> Delivered in 3–7 days on average</li>
							            <li><span class="emoji">🌱</span> 1 tree planted for every order</li>
							            <li><span class="emoji">⭐️</span> Average rating 4.92</li>
							            <li><span class="emoji">💯</span> 100% money back guarantee</li>
							        </ul>
							        <div class="text">
							            <h4>Any questions</h4>
							            <p>Get in <a href="https://englishcyclist.com/pages/contact-us" target="_blank">touch</a> and I will get back to you as soon as I can<br>– Rob</p>
							        </div>
						    	</div>
							</div>
							 <footer>
						        <div class="content">
						            <div class="form-group d-flex">
							            <input class="mt-2" type="checkbox" id="confirm" name="confirm">
							            <label for="confirm">I have reviewed the file and am happy for it to go to print</label>
							        </div>
						        </div>
						        <div class="action d-flex">
						            <a class="button large tertiary edit" data-action-target="review_modify">Edit</a>
						            <a class="button large primary checkout disabled" data-action-target="review_checkout">Checkout</a>
						        </div>
						    </footer>
						</section>




					
				</div>
			</div>

			<div class="col-md-8">
				<main class="bg-map-main">
					<section class="preview">
						<section class="poster">
							<section class="layer map">

								<!-- basemap -->
								<!-- iframe -->
								<iframe class="basemap" src="https://system.englishcyclist.com/2.0/basemap/?_=653354901" frameborder="0"></iframe>

							</section>
							<section class="layer overlay"></section>
							<section class="layer activity">

								<!-- point -->
								<div class="point clone"></div>

								<!-- basemap -->
								<!-- iframe -->
								<iframe class="basemap" src="https://mapeditor.arhamsoft.info/basemap/?_=653354902" frameborder="0"></iframe>

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
								<iframe class="basemap" src="https://mapeditor.arhamsoft.info/basemap/?_=653354903" frameborder="0"></iframe>

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

				</main>
				
			</div>
		</div>
	</div>
</section>




	

<?php }