<section id="content1" class="section">
    <div class="cmaps container">
        <article>
            <div class="row col-sm-10 margin-20">
                <% include BreadCrumbs %>
                <h3>$Title</h3>
                <div class="content">
                    $Content
                    <div id="cmaps_map_ui">
                        <div id="map-canvas"></div>
                        <div class="leaflet-bar cmaps-btn"><a id="menuButton" href="javascript:openSideBar()">Options</a></div>
                        <% if $Feedback %>
                        <div id="cmaps_notification" class="$Feedback.Status">
                            $Feedback.Message
                        </div>
                        <% end_if %>
                        <div id="cmaps_registration" <% if $Registered %> style="display:block" <% end_if %>>
                            <a id="close_registration" href="javascript:showEntryForm()">×</a>
                            <% if $Registered == 1%>
                            <p>Your LibreOffice Contributor Maps listing has been submitted successfully. 
                            However, before we can publish this listing, you have to confirm that you have 
                            made this request by following the instructions we have sent to you via email. 
                            If you don't find anything in your Inbox, please check your Spam folder and add
                            <strong>hostmaster@documentfoundation.org</strong> to your contact list. </p>
                            <% else_if $Registered == 3 %>
                            <p>In order to edit your listing, please change the fields below. After editing, 
                            the listing, your listing will update immediately. Please note that you have to 
                            choose a minimum of 1 skill. If you change your email address, your listing will
                            be not be shown anymore until you use the link we will send you after submission.</p>
                            $RegistrationForm
                            <% else %>
                            <% if $Registered == 2 %><p class="heading_error">We could not register your listing because of some errors. Please fix them and try again. </p><%end_if %>
                            <% if $Registered == 4 %><p class="heading_error">We could not change your listing because of some errors. Please fix them and try again. </p><% end_if %>
                            <p>In order to be listed in the LibreOffice Contributor Maps, just fill in this
                            form and you will be included in the map. Please note that you need to select 
                            a minimum of 1 skill. Please note that the email address you submit will be
                            shown in public. </p>
                            $RegistrationForm
                            <% end_if %>
                        </div>
                        <div id="cmaps_editemail">
                            <a id="close_registration" href="javascript:showEditForm()">×</a>
                            <p>In order to edit or delete your listing, please enter your email address in the form below.</p>
                            $RequestEditForm
                            <div id="cmaps_deletewarning">
                                <p><input type="checkbox" id="cmaps_warning_checkbox" /> I understand that deleting an entry is permanent and can't be undone. </p>
                                <p>If you request the deletion of your entry, we will send you an email with a link that you can visit in order to verify that you
                                made the request. The link will expire in a day. </p>
                            </div>
                        </div>
                        <div id="blanket-mobile"></div>
                        <div class="toc-mobile">
                            <div class="leaflet-bar cmaps-btn"><a href="javascript:openSideBar()" style="margin:5px;width:90px;">Close options</a></div>
                            <a href="javascript:checkAll()">Show everyone</a><br />
                            <a href="javascript:unCheckAll()">Hide everyone</a><br />
                            <div class="toc_menu">
                                <label>
                                    <input type="checkbox" name="Design" checked="true" onclick="updateMarkers()"/> Design
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Development" checked="true" onclick="updateMarkers()"/> Development
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Documentation" checked="true" onclick="updateMarkers()"/> Documentation
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Infrastructure" checked="true" onclick="updateMarkers()"/> Infrastructure
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Localisation" checked="true" onclick="updateMarkers()"/> Localisation
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Marketing" checked="true" onclick="updateMarkers()"/> Marketing
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Quality_Assurance" checked="true" onclick="updateMarkers()"/> Quality Assurance
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Base" checked="true" onclick="updateMarkers()"/> Support - Base
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Calc" checked="true" onclick="updateMarkers()"/> Support - Calc
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Draw" checked="true" onclick="updateMarkers()"/> Support - Draw
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Impress" checked="true" onclick="updateMarkers()"/> Support - Impress
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Math" checked="true" onclick="updateMarkers()"/> Support - Math
                                </label><br />
                                <label>
                                    <input type="checkbox" name="Writer" checked="true" onclick="updateMarkers()"/> Support - Writer
                                </label>
                                <br />
                                <button onclick="showEntryForm()">Add Entry</button><button onclick="showEditForm()">Edit Entry</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 toc">
                <a href="javascript:checkAll()">Show everyone</a><br />
                <a href="javascript:unCheckAll()">Hide everyone</a><br />
                <div class="toc_menu">
                    <label>
                        <input type="checkbox" name="Design" checked="true" onclick="updateMarkers()"/> Design
                    </label><br />
                    <label>
                        <input type="checkbox" name="Development" checked="true" onclick="updateMarkers()"/> Development
                    </label><br />
                    <label>
                        <input type="checkbox" name="Documentation" checked="true" onclick="updateMarkers()"/> Documentation
                    </label><br />
                    <label>
                        <input type="checkbox" name="Infrastructure" checked="true" onclick="updateMarkers()"/> Infrastructure
                    </label><br />
                    <label>
                        <input type="checkbox" name="Localisation" checked="true" onclick="updateMarkers()"/> Localisation
                    </label><br />
                    <label>
                        <input type="checkbox" name="Marketing" checked="true" onclick="updateMarkers()"/> Marketing
                    </label><br />
                    <label>
                        <input type="checkbox" name="Quality_Assurance" checked="true" onclick="updateMarkers()"/> Quality Assurance
                    </label><br />
                    <label>
                        <input type="checkbox" name="Base" checked="true" onclick="updateMarkers()"/> Support - Base
                    </label><br />
                    <label>
                        <input type="checkbox" name="Calc" checked="true" onclick="updateMarkers()"/> Support - Calc
                    </label><br />
                    <label>
                        <input type="checkbox" name="Draw" checked="true" onclick="updateMarkers()"/> Support - Draw
                    </label><br />
                    <label>
                        <input type="checkbox" name="Impress" checked="true" onclick="updateMarkers()"/> Support - Impress
                    </label><br />
                    <label>
                        <input type="checkbox" name="Math" checked="true" onclick="updateMarkers()"/> Support - Math
                    </label><br />
                    <label>
                        <input type="checkbox" name="Writer" checked="true" onclick="updateMarkers()"/> Support - Writer
                    </label>
                    <br />
                    <button onclick="showEntryForm()">Add Entry</button><button onclick="showEditForm()">Edit/Delete Entry</button>
                </div>
            </div>
            <% require css("contributor_maps/templates/css/style.css") %>
            <% require css("contributor_maps/templates/leaflet/leaflet.css") %>
            <% require javascript("https://maps.googleapis.com/maps/api/js?v=3.exp") %>
            <% require javascript("contributor_maps/templates/leaflet/leaflet.js") %>
            <script type="text/javascript">
                function DataSet(name, email, skills, location) {
                    this.name = name;
                    this.email = email;
                    this.skills = skills;
                    this.location = location;
                }
                function Skills(Base, Calc, Design, Development, Documentation, Draw, Impress, Infrastructure, Localisation, Marketing, Math, Quality_Assurance, Writer) {
                    this.Base = Base;
                    this.Calc = Calc;
                    this.Design = Design;
                    this.Development = Development;
                    this.Documentation = Documentation;
                    this.Draw = Draw;
                    this.Impress = Impress;
                    this.Infrastructure = Infrastructure;
                    this.Localisation = Localisation;
                    this.Marketing = Marketing;
                    this.Math = Math;
                    this.Quality_Assurance = Quality_Assurance;
                    this.Writer = Writer;
                }
                var data = [
                <% loop $DataOutput %>
                    new DataSet("$Name $Surname", "$Email",
                        new Skills($Skills_Base, $Skills_Calc, $Skills_Design, $Skills_Dev, $Skills_Doc, $Skills_Draw, $Skills_Impress, $Skills_Infra, $Skills_l10n, $Skills_Marketing, $Skills_Math, $Skills_QA, $Skills_Writer),
                        "$Location"),
                <% end_loop %>
                ]
            </script>
            <% require javascript("contributor_maps/templates/js/logic.js") %>
        </article>
    </div>
</section>