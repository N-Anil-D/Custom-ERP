SELECT item_id, warehouse_id, count(*) FROM rdglobal_portalapp.erp_items_warehouses
group by item_id, warehouse_id
having count(*) > 1