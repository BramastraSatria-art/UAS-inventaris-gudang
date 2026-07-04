let keranjang = [];

function tambahKeKeranjang() {
    const select = document.getElementById('pilihBarang');
    const jumlah = parseInt(document.getElementById('jumlahBarang').value);

    if (!select.value) {
        alert('Pilih barang terlebih dahulu.');
        return;
    }

    if (jumlah < 1) {
        alert('Jumlah harus lebih dari 0.');
        return;
    }

    const id = select.value;
    const nama = select.options[select.selectedIndex].dataset.nama;
    const harga = parseInt(select.options[select.selectedIndex].dataset.harga);
    const stok = parseInt(select.options[select.selectedIndex].dataset.stok);

    if (jumlah > stok) {
        alert('Jumlah melebihi stok tersedia (' + stok + ').');
        return;
    }

    const index = keranjang.findIndex(item => item.id === id);
    if (index !== -1) {
        keranjang[index].jumlah += jumlah;
    } else {
        keranjang.push({ id, nama, harga, jumlah });
    }

    renderKeranjang();
}

function hapusDariKeranjang(index) {
    if (confirm('Yakin hapus item ini dari keranjang?')) {
        keranjang.splice(index, 1);
        renderKeranjang();
    }
}

function renderKeranjang() {
    const tbody = document.getElementById('isiKeranjang');
    tbody.innerHTML = '';
    let total = 0;

    keranjang.forEach((item, index) => {
        const subtotal = item.harga * item.jumlah;
        total += subtotal;
        tbody.innerHTML += `
            <tr>
                <td>${item.nama}</td>
                <td>${item.jumlah}</td>
                <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
                <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                <td><button class="btn btn-sm btn-danger" onclick="hapusDariKeranjang(${index})">Hapus</button></td>
            </tr>
        `;
    });

    document.getElementById('totalHarga').textContent = total.toLocaleString('id-ID');
}

function submitPenjualan() {
    if (keranjang.length === 0) {
        alert('Keranjang masih kosong.');
        return false;
    }
    document.getElementById('inputKeranjang').value = JSON.stringify(keranjang);
    return true;
}