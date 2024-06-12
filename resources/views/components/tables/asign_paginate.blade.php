<section id="paginations" class="section table-footer footer-form px-4 pagination">
    <div>
      <div><span>Rows:</span></div>
      <div>
        <select class="form-select form-select-sm" aria-label="per_page" id="per_page">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </div>
      <div class="paginate">
        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="pagination">
          <div class="btn-group" role="group" aria-label="First group">
            <button type="button" class="btn btn-outline-secondary btn-light btn-sm" id="down">
              <i class='bx bx-chevron-left'></i>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-light btn-sm">
              <span id="from"></span> of <span id="to"></span>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-light btn-sm" id="up">
              <i class='bx bx-chevron-right'></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>