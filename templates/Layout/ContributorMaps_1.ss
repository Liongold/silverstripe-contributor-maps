<div class="content-container unit size3of4 lastUnit">
    <article>
        <h1>$Title</h1>
        <div class="content">
            $Content
            <div id="cmaps_registration">
                $RegistrationForm
            </div>
            <div id="cmaps_map">
                <div id="map-canvas"></div>
                <div id="toc">
                    <%-- --%>
                    <a href="javascript:checkAll()">Show everyone</a>
                    <a href="javascript:unCheckAll()">Hide everyone</a>
                    <div class="toc_menu">
                        <label>
                            <input type="checkbox" name="Base" checked="true" onclick="updateMarkers()"/> Base
                        </label><br />
                        <label>
                            <input type="checkbox" name="Calc" checked="true" onclick="updateMarkers()"/> Calc
                        </label><br />
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
                            <input type="checkbox" name="Draw" checked="true" onclick="updateMarkers()"/> Draw
                        </label><br />
                        <label>
                            <input type="checkbox" name="Impress" checked="true" onclick="updateMarkers()"/> Impress
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
                            <input type="checkbox" name="Math" checked="true" onclick="updateMarkers()"/> Math
                        </label><br />
                        <label>
                            <input type="checkbox" name="Quality_Assurance" checked="true" onclick="updateMarkers()"/> Quality Assurance
                        </label><br />
                        <label>
                            <input type="checkbox" name="Writer" checked="true" onclick="updateMarkers()"/> Writer
                        </label><br />
                        <br />
                        <button onclick="showEntryForm()">Add Entry</button>
                    </div>
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
                        new Skills($Skills_Base, $Skills_Calc, $Skills_Design, $Skills_Dev, $Skills_Doc, $Skills_Draw, $Skills_Impress, $Skills_Infra, $Skills_l10n, $Skills_Marketing, $Skills_Math, $Skills_Math, $Skills_QA, $Skills_Writer),
                        "$Location"),
                <% end_loop %>
                ]
            </script>
            <% require javascript("contributor_maps/templates/js/logic.js") %>
        </div>
    </article>
</div>