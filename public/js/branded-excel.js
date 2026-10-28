/**
 * Branded Excel exports (Gulf Horizons Group).
 *
 * Every sheet follows the same layout as the HR "Employee Age Report" template:
 *
 *   row  1        spacing
 *   row  2        report title        (merged, Arial 17 bold, crimson)
 *   row  4        report date         (merged, Arial 11)
 *   row  6        subtitle / scope    (merged, Arial 10)
 *   rows 7-10     spacing             (logo sits top-right of the title block)
 *   row 11        table header        (dark fill, white bold)
 *   row 12+       data rows           (banded white / light grey)
 *   last row      optional total      (blush fill, crimson bold)
 *   +3 rows       footer              (merged, Arial 10 bold, crimson)
 *
 * Needs ExcelJS (browser global `ExcelJS`, or pass the module in from Node).
 */
(function (root, factory) {
    if (typeof module === 'object' && module.exports) {
        module.exports = factory();
    } else {
        root.BrandedExcel = factory();
    }
})(typeof self !== 'undefined' ? self : this, function () {
    'use strict';

    const BRAND = {
        crimson: 'FFA52939',
        ink: 'FF343638',
        blush: 'FFF5E6E9',
        band: 'FFF3F4F4',
        white: 'FFFFFFFF',
    };
    const FONT = 'Arial';
    const FOOTER_TEXT = 'GULF HORIZONS GROUP  |  HUMAN RESOURCES';
    const HEADER_ROW = 11;
    const MIN_GRID_COLS = 5;          // A..D hold the title block, the last column holds the logo
    const LOGO_COL_WIDTH = 38;
    const LOGO_SIZE = { width: 250, height: 154 };
    const TOP_ROW_HEIGHTS = [24, 31.95, 24, 24, 24, 24, 24, 24, 24, 24];

    /** Arabic sheets are right-to-left (title block on the right, logo on the left). */
    let RTL = true;

    function setRtl(value) {
        RTL = !!value;
    }

    function reportDateText(date) {
        const d = date || new Date();
        let formatted;
        try {
            formatted = d.toLocaleDateString('ar-SA-u-ca-gregory-nu-latn', { day: 'numeric', month: 'long', year: 'numeric' });
        } catch (e) {
            formatted = d.toISOString().slice(0, 10);
        }
        return 'تاريخ التقرير: ' + formatted;
    }

    function safeSheetName(name, used) {
        let clean = String(name || 'Sheet').replace(/[\\\/\?\*\[\]:]/g, ' ').trim().slice(0, 31) || 'Sheet';
        let candidate = clean;
        let i = 2;
        while (used.has(candidate.toLowerCase())) {
            const suffix = ' ' + i++;
            candidate = clean.slice(0, 31 - suffix.length) + suffix;
        }
        used.add(candidate.toLowerCase());
        return candidate;
    }

    /** Excel-friendly value: numeric-looking text becomes a number (IDs with leading zeros stay text). */
    function toCellValue(v) {
        if (v === null || v === undefined) return '';
        if (typeof v === 'number') return v;
        const s = String(v).trim();
        if (/^-?\d{1,15}(\.\d+)?$/.test(s) && !/^0\d/.test(s)) return Number(s);
        return s;
    }

    function create(ExcelJSImpl, logoBuffer) {
        const Impl = ExcelJSImpl || (typeof ExcelJS !== 'undefined' ? ExcelJS : null);
        if (!Impl) throw new Error('ExcelJS library is not loaded');

        const wb = new Impl.Workbook();
        wb.creator = 'Gulf Horizons Group';
        wb.created = new Date();
        wb.__usedNames = new Set();
        wb.__logoId = logoBuffer ? wb.addImage({ buffer: logoBuffer, extension: 'png' }) : null;
        return wb;
    }

    /** Builds the shared header block (logo, title, date, subtitle) and returns layout info. */
    function startSheet(wb, spec, tableCols) {
        const gridCols = Math.max(tableCols, MIN_GRID_COLS);
        const ws = wb.addWorksheet(safeSheetName(spec.name || spec.title, wb.__usedNames), {
            properties: { tabColor: { argb: BRAND.crimson } },
            views: [{ showGridLines: false, rightToLeft: RTL }],
            pageSetup: { paperSize: 9, orientation: 'portrait', fitToPage: true, fitToWidth: 1, fitToHeight: 0, horizontalCentered: true },
        });

        // Column widths: table columns as requested, spare columns padded, last one sized for the logo.
        const widths = spec.widths || [];
        for (let c = 1; c <= gridCols; c++) {
            let w = widths[c - 1];
            if (w === undefined) w = c === gridCols ? LOGO_COL_WIDTH : 24;
            if (c === gridCols && c > tableCols) w = LOGO_COL_WIDTH;
            ws.getColumn(c).width = w;
        }

        TOP_ROW_HEIGHTS.forEach((h, i) => { ws.getRow(i + 1).height = h; });

        const titleEnd = gridCols - 1;
        const textAlign = { vertical: 'middle', readingOrder: RTL ? 'rtl' : 'ltr' };

        ws.mergeCells(2, 1, 2, titleEnd);
        Object.assign(ws.getCell(2, 1), {
            value: spec.title || '',
            font: { name: FONT, size: 17, bold: true, color: { argb: BRAND.crimson } },
            alignment: textAlign,
        });

        ws.mergeCells(4, 1, 4, titleEnd);
        Object.assign(ws.getCell(4, 1), {
            value: spec.dateText || reportDateText(),
            font: { name: FONT, size: 11, color: { argb: BRAND.ink } },
            alignment: textAlign,
        });

        if (spec.subtitle) {
            ws.mergeCells(6, 1, 6, titleEnd);
            Object.assign(ws.getCell(6, 1), {
                value: spec.subtitle,
                font: { name: FONT, size: 10, color: { argb: BRAND.ink } },
                alignment: textAlign,
            });
        }

        if (wb.__logoId !== null) {
            ws.addImage(wb.__logoId, {
                tl: { col: gridCols - 1 + 0.03, row: 0.3 },
                ext: LOGO_SIZE,
                editAs: 'oneCell',
            });
        }

        // Table header
        const headers = spec.headers || [];
        const headerRow = ws.getRow(HEADER_ROW);
        headerRow.height = 30;
        headers.forEach((h, i) => {
            Object.assign(headerRow.getCell(i + 1), {
                value: h,
                font: { name: FONT, size: 11, bold: true, color: { argb: BRAND.white } },
                fill: { type: 'pattern', pattern: 'solid', fgColor: { argb: BRAND.ink } },
                alignment: { horizontal: 'center', vertical: 'middle', wrapText: true, readingOrder: RTL ? 'rtl' : 'ltr' },
            });
        });

        return { ws, gridCols };
    }

    function dataCell(cell, value, band, numFmt) {
        cell.value = toCellValue(value);
        cell.font = { name: FONT, size: 11, color: { argb: BRAND.ink } };
        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: band ? BRAND.band : BRAND.white } };
        cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true, readingOrder: RTL ? 'rtl' : 'ltr' };
        if (numFmt && typeof cell.value === 'number') cell.numFmt = numFmt;
    }

    function totalCell(cell, value, numFmt) {
        cell.value = value;
        cell.font = { name: FONT, size: 11, bold: true, color: { argb: BRAND.crimson } };
        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: BRAND.blush } };
        cell.alignment = { horizontal: 'center', vertical: 'middle', readingOrder: RTL ? 'rtl' : 'ltr' };
        if (numFmt && typeof value === 'number') cell.numFmt = numFmt;
    }

    function addFooter(ws, lastRow, gridCols) {
        const footerRow = lastRow + 3;
        ws.mergeCells(footerRow, 1, footerRow, gridCols);
        Object.assign(ws.getCell(footerRow, 1), {
            value: FOOTER_TEXT,
            font: { name: FONT, size: 10, bold: true, color: { argb: BRAND.crimson } },
            alignment: { vertical: 'middle' },
        });
        ws.getRow(footerRow).height = 24;
    }

    /**
     * Plain table sheet.
     * spec: { name, title, subtitle, headers[], rows[][], widths[], numFmts{col: fmt},
     *         total: { label, sumCols: [colIndex...] } }
     */
    function addTableSheet(wb, spec) {
        const headers = spec.headers || [];
        const { ws, gridCols } = startSheet(wb, spec, headers.length);
        const rows = spec.rows || [];
        const numFmts = spec.numFmts || {};

        rows.forEach((values, r) => {
            const row = ws.getRow(HEADER_ROW + 1 + r);
            row.height = 24;
            headers.forEach((_, c) => dataCell(row.getCell(c + 1), values[c], r % 2 === 1, numFmts[c]));
        });

        let lastRow = HEADER_ROW + Math.max(rows.length, 1);

        if (spec.total) {
            lastRow = HEADER_ROW + rows.length + 1;
            const row = ws.getRow(lastRow);
            row.height = 30;
            headers.forEach((_, c) => totalCell(row.getCell(c + 1), '', numFmts[c]));
            totalCell(row.getCell(1), spec.total.label || 'الإجمالي', null);
            (spec.total.sumCols || []).forEach(c => {
                const sum = rows.reduce((acc, values) => {
                    const n = toCellValue(values[c]);
                    return acc + (typeof n === 'number' ? n : 0);
                }, 0);
                totalCell(row.getCell(c + 1), sum, numFmts[c]);
            });
        }

        addFooter(ws, lastRow, gridCols);
        return ws;
    }

    /**
     * Count sheet — the layout of the template's summary sheet: label | employee count | share, then a total.
     * spec: { name, title, subtitle, labelHeader, items: [{label, count}], widths[], labelNumFmt }
     */
    function addCountSheet(wb, spec) {
        const items = spec.items || [];
        const total = items.reduce((acc, it) => acc + (Number(it.count) || 0), 0);

        return addTableSheet(wb, {
            name: spec.name,
            title: spec.title,
            subtitle: spec.subtitle,
            headers: [spec.labelHeader || 'التصنيف', 'عدد الموظفين', 'النسبة من الموظفين'],
            widths: spec.widths || [28, 20, 22],
            numFmts: { 0: spec.labelNumFmt || null, 1: '0', 2: '0.0%' },
            rows: items.map(it => [it.label, Number(it.count) || 0, total ? (Number(it.count) || 0) / total : 0]),
            total: items.length ? { label: 'الإجمالي', sumCols: [1, 2] } : null,
        });
    }

    /** Browser helpers ------------------------------------------------------ */

    async function loadLogo(url) {
        try {
            const res = await fetch(url, { credentials: 'same-origin' });
            if (!res.ok) return null;
            return await res.arrayBuffer();
        } catch (e) {
            console.warn('Excel logo could not be loaded', e);
            return null;
        }
    }

    async function download(wb, fileName) {
        const buffer = await wb.xlsx.writeBuffer();
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        a.remove();
        setTimeout(() => URL.revokeObjectURL(url), 1500);
    }

    /** Reads a two-column [label | count] statistics table from the page. */
    function readCountTable(table, labelFn) {
        const firstTh = table.querySelector('thead th');
        const labelHeader = firstTh ? firstTh.textContent.trim() : 'التصنيف';
        const items = [];

        table.querySelectorAll('tbody tr').forEach(tr => {
            const tds = tr.querySelectorAll('td');
            if (tds.length < 2) return;                      // "no data" placeholder rows
            const label = labelFn ? labelFn(tds[0]) : tds[0].textContent.trim();
            const count = parseInt(tds[1].textContent.replace(/[^\d]/g, ''), 10) || 0;
            items.push({ label, count });
        });

        return { labelHeader, items };
    }

    /** The statistics tables that exist on the project pages. */
    const STAT_TABLES = [
        { id: 'nationalitiesTable', name: 'جنسيات الموظفين', title: 'إحصائيات جنسيات الموظفين', about: 'توزيع الموظفين حسب الجنسية' },
        { id: 'ageGroupsTable', name: 'الفئات العمرية', title: 'إحصائيات الفئات العمرية', about: 'العمر بالسنوات المكتملة حتى تاريخ التقرير' },
        { id: 'salariesTable', name: 'توزيع الرواتب', title: 'إحصائيات توزيع الرواتب', about: 'توزيع الموظفين حسب الراتب (ر.س)', salary: true },
        { id: 'rolesTable', name: 'أدوار الموظفين', title: 'إحصائيات أدوار الموظفين', about: 'توزيع الموظفين حسب الدور الوظيفي' },
    ];

    /** Cell text without the flag / icon markup the page puts next to some labels. */
    function plainLabel(td) {
        const span = td.querySelector('.flex.flex-col.items-start.gap-y-2 span');
        return (span ? span.textContent : td.textContent).trim();
    }

    /**
     * Exports one or more of the page's statistics tables into a single branded workbook.
     * opts: { tables: ['ageGroupsTable', ...] (default: all that exist), fileName, logoUrl, scope }
     * Returns true when a file was produced, false when no table was found.
     */
    async function exportStatTables(opts) {
        const wantedIds = opts.tables || STAT_TABLES.map(t => t.id);
        const wb = create(null, await loadLogo(opts.logoUrl));
        let added = 0;

        STAT_TABLES.filter(def => wantedIds.includes(def.id)).forEach(def => {
            const table = document.getElementById(def.id);
            if (!table) return;

            const { labelHeader, items } = readCountTable(table, plainLabel);
            if (def.salary) {
                items.forEach(it => { it.label = Number(String(it.label).replace(/[^\d.]/g, '')) || it.label; });
            }

            addCountSheet(wb, {
                name: def.name,
                title: def.title,
                subtitle: [opts.scope, def.about].filter(Boolean).join(' | '),
                labelHeader,
                items,
                labelNumFmt: def.salary ? '#,##0' : null,
            });
            added++;
        });

        if (!added) throw new Error('لم يتم العثور على أي جداول للتصدير');

        await download(wb, opts.fileName + '_' + new Date().toISOString().slice(0, 10) + '.xlsx');
        return true;
    }

    return { BRAND, setRtl, create, addTableSheet, addCountSheet, loadLogo, download, readCountTable, exportStatTables, reportDateText, toCellValue };
});
