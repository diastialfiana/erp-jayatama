<div class="ribbon-toolbar">
    <!-- FILE GROUP -->
    <div class="ribbon-group">
        <div class="ribbon-actions">
            <button class="ribbon-btn" title="New Record (Ctrl+N)" @click="$dispatch('ribbon-action', 'new')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                <span>New</span>
            </button>
            <div class="ribbon-grid">
                <button class="ribbon-btn small" title="Edit Current Record" @click="$dispatch('ribbon-action', 'edit')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    <span>Edit</span>
                </button>
                <button class="ribbon-btn small" title="Delete Current Record" @click="$dispatch('ribbon-action', 'delete')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    <span>Delete</span>
                </button>
                <button class="ribbon-btn small" title="Free / Release Record" @click="$dispatch('ribbon-action', 'free')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                    <span>Free</span>
                </button>
            </div>
        </div>
        <div class="ribbon-group-label">File</div>
    </div>

    <!-- TOOLS GROUP -->
    <div class="ribbon-group">
        <div class="ribbon-actions">
            <button class="ribbon-btn" title="Save Changes (Ctrl+S)" @click="$dispatch('ribbon-action', 'save')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                <span>Save</span>
            </button>
            <button class="ribbon-btn" title="Undo Last Action" @click="$dispatch('ribbon-action', 'undo')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                <span>Undo</span>
            </button>
            <div class="ribbon-grid">
                <button class="ribbon-btn small" title="Save As New Record" @click="$dispatch('ribbon-action', 'save-as')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/><circle cx="12" cy="17" r="2"/></svg>
                    <span>Save As</span>
                </button>
                <button class="ribbon-btn small" title="Preview / Print" @click="$dispatch('ribbon-action', 'preview')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    <span>Preview</span>
                </button>
                <button class="ribbon-btn small" title="Refresh Data" @click="$dispatch('ribbon-action', 'refresh')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                    <span>Refresh</span>
                </button>
            </div>
            <div class="ribbon-grid">
                <button class="ribbon-btn small" title="Find / Search (Ctrl+F)" @click="$dispatch('ribbon-action', 'find')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <span>Find</span>
                </button>
                <button class="ribbon-btn small" title="Go To Record" @click="$dispatch('ribbon-action', 'goto')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                    <span>GoTo</span>
                </button>
                <button class="ribbon-btn small" title="Print Barcode" @click="$dispatch('ribbon-action', 'barcode')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5v14"/><path d="M8 5v14"/><path d="M12 5v14"/><path d="M17 5v14"/><path d="M21 5v14"/></svg>
                    <span>Barcode</span>
                </button>
            </div>
            <button class="ribbon-btn" title="Re-Send Document" @click="$dispatch('ribbon-action', 'resend')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                <span>Re-Send</span>
            </button>
        </div>
        <div class="ribbon-group-label">Tools</div>
    </div>

    <!-- SKINS GROUP -->
    <div class="ribbon-group">
        <div class="skin-grid">
            <div class="skin-item" style="background: #1e3a8a;" title="Blue Skin" @click="$dispatch('change-skin', '#1e3a8a')"></div>
            <div class="skin-item" style="background: #ef4444;" title="Red Skin" @click="$dispatch('change-skin', '#ef4444')"></div>
            <div class="skin-item" style="background: #10b981;" title="Green Skin" @click="$dispatch('change-skin', '#10b981')"></div>
            <div class="skin-item" style="background: #f59e0b;" title="Amber Skin" @click="$dispatch('change-skin', '#f59e0b')"></div>
            <div class="skin-item" style="background: #6366f1;" title="Indigo Skin" @click="$dispatch('change-skin', '#6366f1')"></div>
            <div class="skin-item" style="background: #ec4899;" title="Pink Skin" @click="$dispatch('change-skin', '#ec4899')"></div>
            <div class="skin-item" style="background: #8b5cf6;" title="Purple Skin" @click="$dispatch('change-skin', '#8b5cf6')"></div>
            <div class="skin-item" style="background: #14b8a6;" title="Teal Skin" @click="$dispatch('change-skin', '#14b8a6')"></div>
            <div class="skin-item" style="background: #334155;" title="Dark Skin" @click="$dispatch('change-skin', '#334155')"></div>
            <div class="skin-item" style="background: #f1f5f9; border: 1px solid #cbd5e1;" title="Light Skin" @click="$dispatch('change-skin', '#f1f5f9')"></div>
        </div>
        <div class="ribbon-group-label">Skins</div>
    </div>
</div>

