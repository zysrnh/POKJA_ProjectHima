/**
 * =========================================================================
 * GOOGLE APPS SCRIPT - POKJA HIMA IF
 * Fitur:
 * 1. Otomatis Menulis ke Sheet 1: "Data Peserta" (Registrasi Mahasiswa)
 * 2. Otomatis Membuat & Mengisi Sheet 2: "Kehadiran" (Rekap 7 Pertemuan P1 - P7)
 * 3. Styling Neubrutalism Rapi, Freeze Row, Auto-Fit Kolom, Header Biru Tua
 * =========================================================================
 * 
 * CARA PASANG DI GOOGLE SPREADSHEET:
 * 1. Buka spreadsheet Google Anda (PesertaPokja).
 * 2. Klik menu "Ekstensi" (Extensions) > "Apps Script".
 * 3. Hapus semua kode yang ada di Apps Script, lalu PASTE SELURUH KODE DI BAWAH INI.
 * 4. Klik tombol "Simpan" (Ctrl+S / Ikon Disket).
 * 5. Klik tombol "Terapkan" (Deploy) > "Kelola Penerapan" (Manage deployments) atau "Penerapan baru" (New deployment).
 *    - Jenis: Web App (Aplikasi Web)
 *    - Jalankan sebagai: Saya (Email Anda)
 *    - Siapa yang memiliki akses: Siapa saja (Anyone) -> PENTING agar sistem bisa mengirim data!
 * 6. Salin URL Web App yang dihasilkan (akhiran /exec) dan simpan ke file .env di Laravel:
 *    GOOGLE_SHEET_WEBHOOK_URL=https://script.google.com/macros/s/.../exec
 */

function doPost(e) {
  try {
    var rawData = e.postData.contents;
    var requestData = JSON.parse(rawData);
    var action = requestData.action || '';
    
    var ss = SpreadsheetApp.getActiveSpreadsheet();
    
    // =====================================================================
    // 1. SINKRONISASI SHEET 1: DATA PESERTA PENDAFTARAN
    // =====================================================================
    var sheetPeserta = ss.getSheetByName("Data Peserta") || ss.getSheetByName("Sheet1") || ss.getSheets()[0];
    sheetPeserta.setName("Data Peserta");
    
    var dataPeserta = requestData.pendaftar || requestData.data || [];
    if (dataPeserta && dataPeserta.length > 0) {
      // Reset sheet & tulis header
      sheetPeserta.clear();
      
      var headerPeserta = [["No", "Timestamp", "Nama Lengkap", "NIM", "Email", "Kelas", "No. WhatsApp"]];
      sheetPeserta.getRange(1, 1, 1, 7).setValues(headerPeserta);
      
      // Styling Header Sheet 1
      var headerRange1 = sheetPeserta.getRange("A1:G1");
      headerRange1.setBackground("#1A467C");
      headerRange1.setFontColor("#FFFFFF");
      headerRange1.setFontWeight("bold");
      headerRange1.setHorizontalAlignment("center");
      headerRange1.setVerticalAlignment("middle");
      sheetPeserta.setRowHeight(1, 35);
      
      var rowsPeserta = [];
      for (var i = 0; i < dataPeserta.length; i++) {
        var p = dataPeserta[i];
        rowsPeserta.push([
          i + 1,
          p.created_at || p.timestamp || '-',
          p.nama || '-',
          "'" + (p.nim || '-'), // Tanda kutip agar NIM angka panjang tidak berformat eksponen
          p.email || '-',
          p.kelas || '-',
          "'" + (p.no_telp || '-')
        ]);
      }
      
      if (rowsPeserta.length > 0) {
        var dataRange1 = sheetPeserta.getRange(2, 1, rowsPeserta.length, 7);
        dataRange1.setValues(rowsPeserta);
        
        // Alignment
        sheetPeserta.getRange(2, 1, rowsPeserta.length, 1).setHorizontalAlignment("center"); // No
        sheetPeserta.getRange(2, 4, rowsPeserta.length, 1).setHorizontalAlignment("center"); // NIM
        sheetPeserta.getRange(2, 6, rowsPeserta.length, 1).setHorizontalAlignment("center"); // Kelas
        sheetPeserta.getRange(2, 7, rowsPeserta.length, 1).setHorizontalAlignment("center"); // No WA
        
        // Border
        dataRange1.setBorder(true, true, true, true, true, true, "#DDDDDD", SpreadsheetApp.BorderStyle.SOLID);
      }
      
      sheetPeserta.setFrozenRows(1);
      for (var c = 1; c <= 7; c++) {
        sheetPeserta.autoResizeColumn(c);
      }
    }
    
    // =====================================================================
    // 2. SINKRONISASI SHEET 2: KEHADIRAN 7 PERTEMUAN (P1 - P7)
    // =====================================================================
    var dataKehadiran = requestData.kehadiran || [];
    if (dataKehadiran && dataKehadiran.length > 0) {
      var sheetKehadiran = ss.getSheetByName("Kehadiran");
      if (!sheetKehadiran) {
        sheetKehadiran = ss.insertSheet("Kehadiran");
      }
      sheetKehadiran.clear();
      
      var headerKehadiran = [["No", "NIM", "Nama Lengkap", "Kelas", "P1", "P2", "P3", "P4", "P5", "P6", "P7", "Total Hadir", "% Kehadiran"]];
      sheetKehadiran.getRange(1, 1, 1, 13).setValues(headerKehadiran);
      
      // Styling Header Sheet 2
      var headerRange2 = sheetKehadiran.getRange("A1:M1");
      headerRange2.setBackground("#0F2A4A");
      headerRange2.setFontColor("#FFFFFF");
      headerRange2.setFontWeight("bold");
      headerRange2.setHorizontalAlignment("center");
      headerRange2.setVerticalAlignment("middle");
      sheetKehadiran.setRowHeight(1, 35);
      
      var rowsKehadiran = [];
      for (var j = 0; j < dataKehadiran.length; j++) {
        var k = dataKehadiran[j];
        rowsKehadiran.push([
          j + 1,
          "'" + (k.nim || '-'),
          k.nama || '-',
          k.kelas || '-',
          k.p1 || '-',
          k.p2 || '-',
          k.p3 || '-',
          k.p4 || '-',
          k.p5 || '-',
          k.p6 || '-',
          k.p7 || '-',
          k.total_hadir !== undefined ? k.total_hadir : 0,
          k.persentase || '0%'
        ]);
      }
      
      if (rowsKehadiran.length > 0) {
        var dataRange2 = sheetKehadiran.getRange(2, 1, rowsKehadiran.length, 13);
        dataRange2.setValues(rowsKehadiran);
        
        // Alignment
        sheetKehadiran.getRange(2, 1, rowsKehadiran.length, 1).setHorizontalAlignment("center"); // No
        sheetKehadiran.getRange(2, 2, rowsKehadiran.length, 1).setHorizontalAlignment("center"); // NIM
        sheetKehadiran.getRange(2, 4, rowsKehadiran.length, 1).setHorizontalAlignment("center"); // Kelas
        sheetKehadiran.getRange(2, 5, rowsKehadiran.length, 7).setHorizontalAlignment("center"); // P1 - P7
        sheetKehadiran.getRange(2, 12, rowsKehadiran.length, 2).setHorizontalAlignment("center"); // Total & %
        
        // Format Warna Hijau untuk HADIR
        var pRange = sheetKehadiran.getRange(2, 5, rowsKehadiran.length, 7);
        var pValues = pRange.getValues();
        var pBackgrounds = [];
        var pFontColors = [];
        
        for (var r = 0; r < pValues.length; r++) {
          var bgRow = [];
          var fontRow = [];
          for (var col = 0; col < 7; col++) {
            if (pValues[r][col] === "HADIR") {
              bgRow.push("#DCFCE7"); // Hijau lembut
              fontRow.push("#166534"); // Hijau gelap
            } else {
              bgRow.push("#FFFFFF");
              fontRow.push("#999999");
            }
          }
          pBackgrounds.push(bgRow);
          pFontColors.push(fontRow);
        }
        pRange.setBackgrounds(pBackgrounds);
        pRange.setFontColors(pFontColors);
        pRange.setFontWeight("bold");
        
        dataRange2.setBorder(true, true, true, true, true, true, "#DDDDDD", SpreadsheetApp.BorderStyle.SOLID);
      }
      
      sheetKehadiran.setFrozenRows(1);
      for (var c2 = 1; c2 <= 13; c2++) {
        sheetKehadiran.autoResizeColumn(c2);
      }
    }
    
    return ContentService.createTextOutput(JSON.stringify({
      status: "success",
      message: "Data peserta dan kehadiran 7 pertemuan berhasil disinkronkan ke Google Sheets."
    })).setMimeType(ContentService.MimeType.JSON);
    
  } catch (error) {
    return ContentService.createTextOutput(JSON.stringify({
      status: "error",
      message: error.toString()
    })).setMimeType(ContentService.MimeType.JSON);
  }
}
