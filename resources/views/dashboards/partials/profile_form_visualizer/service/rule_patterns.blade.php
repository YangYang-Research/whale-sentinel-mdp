<p><strong>Regex Patterns</strong></p>
<div class="form-group">
    <label>Type</label>
    <select class="form-control" id="regex-type">
        <option value="">-- Select Type --</option>
        <option value="dectect_sqli">SQL Injection</option>
        <option value="detect_xss">Cross Site Scripting</option>
        <option value="detect_unknown_attack">Unknow Attack</option>
        <option value="secure_redirect">Extend Domain</option>
    </select>
</div>

<div class="form-group">
    <label>Add Your Regex</label>
    <div class="d-flex">
        <input type="text" class="form-control mr-2" id="custom-regex-key" placeholder="Name">
        <input type="text" class="form-control mr-2" id="custom-regex-value" placeholder="Value">
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-custom-regex">Add</button>
    </div>
</div>

<ul class="list-group mt-2" id="custom-regex-list">
    <!-- Custom regex will be listed here -->
</ul>
