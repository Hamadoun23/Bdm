/**
 * Export des graphiques Chart.js en Word (.doc, HTML interprété par Word).
 * Appeler gdaInitChartExports() après l’instanciation des graphiques.
 */
(function (global) {
    'use strict';

    function escapeHtml(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function saveBlob(blob, filename) {
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        setTimeout(function () {
            URL.revokeObjectURL(url);
        }, 60000);
    }

    function collectSnapshots(items) {
        var out = [];
        if (typeof Chart === 'undefined') {
            return out;
        }
        (items || []).forEach(function (item) {
            var el = document.getElementById(item.id);
            if (!el) {
                return;
            }
            var ch = Chart.getChart(el);
            if (!ch) {
                return;
            }
            out.push({
                title: item.title || item.id,
                dataUrl: ch.toBase64Image('image/png', 1.0),
            });
        });
        return out;
    }

    function exportWord(snapshots, fileBase, docTitle) {
        var html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word"><head><meta charset="utf-8"><title>'
            + escapeHtml(docTitle) + '</title></head><body>';
        html += '<h1>' + escapeHtml(docTitle) + '</h1>';
        snapshots.forEach(function (snap) {
            html += '<h2>' + escapeHtml(snap.title) + '</h2>';
            html += '<p><img width="640" src="' + snap.dataUrl + '" alt=""></p><br/>';
        });
        html += '</body></html>';
        var blob = new Blob(['\ufeff', html], { type: 'application/msword;charset=utf-8' });
        saveBlob(blob, String(fileBase || 'graphiques') + '.doc');
    }

    function bindBar(root) {
        var raw = root.getAttribute('data-gda-export');
        if (!raw) {
            return;
        }
        var config;
        try {
            config = JSON.parse(raw);
        } catch (e) {
            return;
        }
        var items = config.items || [];

        function runWord() {
            var snaps = collectSnapshots(items);
            if (!snaps.length) {
                window.alert('Aucun graphique à exporter. Vérifiez que les données sont affichées.');
                return;
            }
            exportWord(snaps, config.fileBase || 'graphiques', config.docTitle || 'Rapport graphiques');
        }

        var w = root.querySelector('.gda-export-word');
        if (w) {
            w.addEventListener('click', function (e) {
                e.preventDefault();
                runWord();
            });
        }
    }

    function initGdaChartExports() {
        document.querySelectorAll('[data-gda-export]').forEach(function (root) {
            if (root.getAttribute('data-gda-export-bound') === '1') {
                return;
            }
            root.setAttribute('data-gda-export-bound', '1');
            bindBar(root);
        });
    }

    global.gdaInitChartExports = initGdaChartExports;
})(window);
