<?php

class SqlGenerator extends CComponent {

    public static function localStock() {
        $sql = "SELECT COALESCE(SUM(quantity_in - quantity_out), 0)
				FROM " . Inventory::model()->tableName() . " 
				WHERE product_id = :product_id AND warehouse_id = :warehouse_id";

        return $sql;
    }

    public static function localStockItemPrice() {
        $sql = "SELECT AVG(price)
				FROM " . Inventory::model()->tableName() . " 
				WHERE product_id = :product_id AND warehouse_id = :warehouse_id";

        return $sql;
    }

    public static function localStockPrice() {
        $sql = "SELECT COALESCE(SUM(quantity_in - quantity_out), 0) * AVG(price)
				FROM " . Inventory::model()->tableName() . " 
				WHERE product_id = :product_id AND warehouse_id = :warehouse_id";

        return $sql;
    }

    public static function globalStock() {
        $sql = "SELECT COALESCE(SUM(quantity_in - quantity_out), 0) 
				FROM " . Inventory::model()->tableName() . " 
				WHERE product_id = :product_id";

        return $sql;
    }

    public static function globalStockItemPrice() {
        $sql = "SELECT AVG(price)
				FROM " . Inventory::model()->tableName() . " 
				WHERE product_id = :product_id";

        return $sql;
    }

    public static function globalStockPrice() {
        $sql = "SELECT COALESCE(SUM(quantity_in - quantity_out), 0) * AVG(price)
				FROM " . Inventory::model()->tableName() . " 
				WHERE product_id = :product_id";

        return $sql;
    }

    public static function stockBeginning() {
        $sql = "SELECT receive.quantity - purchase_return.quantity + adjustment.quantity - transfer_from.quantity + transfer_to.quantity - delivery.quantity + sale_return.quantity AS current_stock FROM
				(
					" . SqlViewGenerator::receiveStock() . "
					WHERE d.product_id = :product_id AND h.date < :start_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) receive
				CROSS JOIN
				(
					" . SqlViewGenerator::purchaseReturnStock() . "
					WHERE d.product_id = :product_id AND h.date < :start_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) purchase_return
				CROSS JOIN
				(
					" . SqlViewGenerator::adjustmentStock() . "
					WHERE d.product_id = :product_id AND h.date < :start_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) adjustment
				CROSS JOIN
				(
					" . SqlViewGenerator::transferStock() . "
					WHERE d.product_id = :product_id AND h.date < :start_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) transfer_from
				CROSS JOIN
				(
					" . SqlViewGenerator::transferStock() . "
					WHERE d.product_id = :product_id AND h.date < :start_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) transfer_to
				CROSS JOIN
				(
					" . SqlViewGenerator::deliveryStock() . "
					WHERE d.product_id = :product_id AND h.date < :start_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) delivery
				CROSS JOIN
				(
					" . SqlViewGenerator::saleReturnStock() . "
					WHERE d.product_id = :product_id AND h.date < :start_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) sale_return";

        return $sql;
    }

    public static function stockEnding() {
        $sql = "SELECT receive.quantity - purchase_return.quantity + adjustment.quantity - transfer_from.quantity + transfer_to.quantity - delivery.quantity + sale_return.quantity AS current_stock FROM
				(
					" . SqlViewGenerator::receiveStock() . "
					WHERE d.product_id = :product_id AND h.date <= :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) receive
				CROSS JOIN
				(
					" . SqlViewGenerator::purchaseReturnStock() . "
					WHERE d.product_id = :product_id AND h.date <= :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) purchase_return
				CROSS JOIN
				(
					" . SqlViewGenerator::adjustmentStock() . "
					WHERE d.product_id = :product_id AND h.date <= :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) adjustment
				CROSS JOIN
				(
					" . SqlViewGenerator::transferStock() . "
					WHERE d.product_id = :product_id AND h.date <= :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) transfer_from
				CROSS JOIN
				(
					" . SqlViewGenerator::transferStock() . "
					WHERE d.product_id = :product_id AND h.date <= :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) transfer_to
				CROSS JOIN
				(
					" . SqlViewGenerator::deliveryStock() . "
					WHERE d.product_id = :product_id AND h.date <= :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) delivery
				CROSS JOIN
				(
					" . SqlViewGenerator::saleReturnStock() . "
					WHERE d.product_id = :product_id AND h.date <= :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) sale_return";

        return $sql;
    }

    public static function stockIn() {
        $sql = "SELECT receive.quantity + adjustment.quantity + transfer_to.quantity + sale_return.quantity AS current_stock FROM
				(
					" . SqlViewGenerator::receiveStock() . "
					WHERE d.product_id = :product_id AND h.date BETWEEN :start_date AND :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) receive
				CROSS JOIN
				(
					" . SqlViewGenerator::adjustmentStock() . "
					WHERE d.product_id = :product_id AND h.date BETWEEN :start_date AND :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) adjustment
				CROSS JOIN
				(
					" . SqlViewGenerator::transferStock() . "
					WHERE d.product_id = :product_id AND h.date BETWEEN :start_date AND :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) transfer_to
				CROSS JOIN
				(
					" . SqlViewGenerator::saleReturnStock() . "
					WHERE d.product_id = :product_id AND h.date BETWEEN :start_date AND :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) sale_return";

        return $sql;
    }

    public static function stockOut() {
        $sql = "SELECT purchase_return.quantity + transfer_from.quantity + delivery.quantity AS current_stock FROM
				(
					" . SqlViewGenerator::purchaseReturnStock() . "
					WHERE d.product_id = :product_id AND h.date BETWEEN :start_date AND :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) purchase_return
				CROSS JOIN
				(
					" . SqlViewGenerator::transferStock() . "
					WHERE d.product_id = :product_id AND h.date BETWEEN :start_date AND :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) transfer_from
				CROSS JOIN
				(
					" . SqlViewGenerator::deliveryStock() . "
					WHERE d.product_id = :product_id AND h.date BETWEEN :start_date AND :end_date AND h.is_inactive = 0 AND d.is_inactive = 0
				) delivery";

        return $sql;
    }

    public static function bankBook() {
        $sql = "SELECT dc.date, a.name AS account, dc.debit, dc.credit, dc.detail_account_id, dc.note
				FROM
				(
					" . SqlViewGenerator::balance() . "
				) dc
				INNER JOIN " . Account::model()->tableName() . " a ON a.id = dc.detail_account_id
				WHERE dc.date BETWEEN :start_date AND :end_date AND dc.is_inactive = 0 AND a.is_inactive = 0
				ORDER BY date ASC, account ASC";

        return $sql;
    }

    public static function beginningBalance() {
        $sql = "SELECT COALESCE(SUM(dc.debit) - SUM(dc.credit), 0) AS beginning_balance FROM
				(
					" . SqlViewGenerator::balance() . "
				) dc
				INNER JOIN " . Account::model()->tableName() . " a ON a.id = dc.detail_account_id
				WHERE dc.account_id = :account_id AND dc.date < :start_date AND dc.is_inactive = 0 AND a.is_inactive = 0";

        return $sql;
    }

    public static function endingBalance() {
        $sql = "SELECT COALESCE(SUM(dc.debit) - SUM(dc.credit), 0) AS ending_balance FROM
				(
					" . SqlViewGenerator::balance() . "
				) dc
				INNER JOIN " . Account::model()->tableName() . " a ON a.id = dc.detail_account_id
				WHERE dc.account_id = :account_id AND dc.date <= :end_date  AND dc.is_inactive = 0 AND a.is_inactive = 0";

        return $sql;
    }

    public static function totalCredit() {
        $sql = "SELECT COALESCE(SUM(dc.credit), 0) AS total_credit FROM
				(
					" . SqlViewGenerator::balance() . "
				) dc
				INNER JOIN " . Account::model()->tableName() . " a ON a.id = dc.detail_account_id
				WHERE dc.account_id = :account_id AND dc.date BETWEEN :start_date AND :end_date";

        return $sql;
    }

//	public static function beginningBalanceLedger()
//	{
//		$sql = "SELECT COALESCE(SUM(dc.debit) - SUM(dc.credit), 0) AS beginning_balance FROM
//				(
//					" . JournalAccounting::model()->tableName() . "
//				) dc
//				INNER JOIN " . Account::model()->tableName() . " a ON a.id = dc.account_id
//				WHERE dc.branch_id = :branch_id AND dc.date < :start_date";
//		
//		return $sql;
//	}
//	
//	public static function endingBalanceLedger()
//	{
//		$sql = "SELECT COALESCE(SUM(dc.debit) - SUM(dc.credit), 0) AS ending_balance FROM
//				(
//					" . JournalAccounting::model()->tableName() . "
//				) dc
//				INNER JOIN " . Account::model()->tableName() . " a ON a.id = dc.account_id
//				WHERE dc.branch_id = :branch_id AND dc.date <= :end_date";
//		
//		return $sql;
//	}

    public static function profitLoss() {

        $sql = "SELECT sale.amount AS sale_amount, purchase.amount AS purchase_amount,
				purchase.amount AS ready_stock,purchase.amount AS cogs,
				sale.amount - purchase.amount  AS gross, 
				expense.amount AS expense_amount, other_income.amount AS other_income_amount, other_expense.amount AS other_expense_amount, 
				sale.amount - purchase.amount - expense.amount - other_expense.amount + other_income.amount AS profit_loss 
				FROM
				(
					SELECT COALESCE(SUM(credit) - SUM(debit), 0) as amount 
					FROM " . JournalAccounting::model()->tableName() . " aj 
					INNER JOIN " . Account::model()->tableName() . " a ON a.id = aj.account_id 
					INNER JOIN " . AccountCategory::model()->tableName() . " ac ON ac.id = a.account_category_id 
					WHERE aj.date BETWEEN :start_date AND :end_date 
				) sale
				CROSS JOIN
				(
					SELECT COALESCE(SUM(debit) - SUM(credit), 0) as amount 
					FROM " . JournalAccounting::model()->tableName() . " aj 
					INNER JOIN " . Account::model()->tableName() . " a ON a.id = aj.account_id 
					INNER JOIN " . AccountCategory::model()->tableName() . " ac ON ac.id = a.account_category_id 
					WHERE  aj.date BETWEEN :start_date AND :end_date 
				) purchase
				CROSS JOIN
				(
					SELECT COALESCE(SUM(credit) - SUM(debit), 0) as amount 
					FROM " . JournalAccounting::model()->tableName() . " aj 
					INNER JOIN " . Account::model()->tableName() . " a ON a.id = aj.account_id 
					INNER JOIN " . AccountCategory::model()->tableName() . " ac ON ac.id = a.account_category_id 
					WHERE  aj.date BETWEEN :start_date AND :end_date 
				) expense
				CROSS JOIN
				(
					SELECT COALESCE(SUM(debit) - SUM(credit), 0) as amount 
					FROM " . JournalAccounting::model()->tableName() . " aj 
					INNER JOIN " . Account::model()->tableName() . " a ON a.id = aj.account_id 
					INNER JOIN " . AccountCategory::model()->tableName() . " ac ON ac.id = a.account_category_id 
					WHERE  aj.date BETWEEN :start_date AND :end_date 
				) other_income
				CROSS JOIN
				(
					SELECT COALESCE(SUM(credit) - SUM(debit), 0) as amount 
					FROM " . JournalAccounting::model()->tableName() . " aj 
					INNER JOIN " . Account::model()->tableName() . " a ON a.id = aj.account_id 
					INNER JOIN " . AccountCategory::model()->tableName() . " ac ON ac.id = a.account_category_id 
					WHERE  aj.date BETWEEN :start_date AND :end_date 
				) other_expense
				
				";


//		$sql = "SELECT sale.amount AS sale_amount, purchase.amount AS purchase_amount, beginning_stock.amount AS beginning_stock_amount, ending_stock.amount AS ending_stock_amount, 
//				beginning_stock.amount + purchase.amount AS ready_stock, beginning_stock.amount + purchase.amount - ending_stock.amount AS cogs,
//				sale.amount - beginning_stock.amount - purchase.amount + ending_stock.amount AS gross, 
//				expense.amount AS expense_amount, other_income.amount AS other_income_amount, other_expense.amount AS other_expense_amount, 
//				sale.amount - beginning_stock.amount - purchase.amount + ending_stock.amount - expense.amount - other_expense.amount + other_income.amount AS profit_loss 
//				FROM
//				(
//					SELECT COALESCE(SUM(credit) - SUM(debit), 0) as amount 
//					FROM ". JournalAccounting::model()->tableName() ." aj 
//					INNER JOIN " . Account::model()->tableName() ." a ON a.id = aj.account_id 
//					INNER JOIN ". AccountCategory::model()->tableName() ." ac ON ac.id = a.account_category_id 
//					WHERE ac.account_category_type_id = 3 AND aj.date BETWEEN :start_date AND :end_date AND aj.branch_id = :branch_id
//				) sale
//				CROSS JOIN
//				(
//					SELECT COALESCE(SUM(debit) - SUM(credit), 0) as amount 
//					FROM ". JournalAccounting::model()->tableName() ." aj 
//					INNER JOIN " . Account::model()->tableName() ." a ON a.id = aj.account_id 
//					INNER JOIN ". AccountCategory::model()->tableName() ." ac ON ac.id = a.account_category_id 
//					WHERE ac.account_category_type_id = 4 AND aj.date BETWEEN :start_date AND :end_date AND aj.branch_id = :branch_id
//				) purchase
//				CROSS JOIN
//				(
//					SELECT COALESCE(SUM(credit) - SUM(debit), 0) as amount 
//					FROM ". JournalAccounting::model()->tableName() ." aj 
//					INNER JOIN " . Account::model()->tableName() ." a ON a.id = aj.account_id 
//					INNER JOIN ". AccountCategory::model()->tableName() ." ac ON ac.id = a.account_category_id 
//					WHERE ac.account_category_type_id = 5 AND aj.date BETWEEN :start_date AND :end_date AND aj.branch_id = :branch_id
//				) expense
//				CROSS JOIN
//				(
//					SELECT COALESCE(SUM(debit) - SUM(credit), 0) as amount 
//					FROM ". JournalAccounting::model()->tableName() ." aj 
//					INNER JOIN " . Account::model()->tableName() ." a ON a.id = aj.account_id 
//					INNER JOIN ". AccountCategory::model()->tableName() ." ac ON ac.id = a.account_category_id 
//					WHERE ac.account_category_type_id = 6 AND aj.date BETWEEN :start_date AND :end_date AND aj.branch_id = :branch_id
//				) other_income
//				CROSS JOIN
//				(
//					SELECT COALESCE(SUM(credit) - SUM(debit), 0) as amount 
//					FROM ". JournalAccounting::model()->tableName() ." aj 
//					INNER JOIN " . Account::model()->tableName() ." a ON a.id = aj.account_id 
//					INNER JOIN ". AccountCategory::model()->tableName() ." ac ON ac.id = a.account_category_id 
//					WHERE ac.account_category_type_id = 7 AND aj.date BETWEEN :start_date AND :end_date AND aj.branch_id = :branch_id
//				) other_expense
//				CROSS JOIN
//				(
//					SELECT (purchase.total_quantity - delivery.total_quantity) * purchase.average AS amount FROM
//					(
//						SELECT SUM(d.quantity * d.unit_price * (1 - d.discount / 100)) / SUM(d.quantity) AS average, SUM(d.quantity) AS total_quantity
//						FROM ". PurchaseHeader::model()->tableName() ." h
//						INNER JOIN  ". PurchaseDetail::model()->tableName() ." d ON h.id = d.purchase_header_id
//						WHERE h.date < :start_date AND h.branch_id = :branch_id
//					) purchase
//					CROSS JOIN
//					(
//						SELECT SUM(d.quantity) AS total_quantity
//						FROM ". DeliveryHeader::model()->tableName() ." h
//						INNER JOIN ". DeliveryDetail::model()->tableName() ." d ON h.id = d.delivery_header_id
//						WHERE h.date < :start_date AND h.branch_id = :branch_id
//					) delivery 
//				) beginning_stock
//				CROSS JOIN
//				(
//					SELECT (purchase.total_quantity - delivery.total_quantity) * purchase.average AS amount FROM
//					(
//						SELECT SUM(d.quantity * d.unit_price * (1 - d.discount / 100)) / SUM(d.quantity) AS average, SUM(d.quantity) AS total_quantity
//						FROM ". PurchaseHeader::model()->tableName() ." h
//						INNER JOIN  ". PurchaseDetail::model()->tableName() ." d ON h.id = d.purchase_header_id
//						WHERE h.date <= :end_date AND h.branch_id = :branch_id
//					) purchase
//					CROSS JOIN
//					(
//						SELECT SUM(d.quantity) AS total_quantity
//						FROM ". DeliveryHeader::model()->tableName() ." h
//						INNER JOIN ". DeliveryDetail::model()->tableName() ." d ON h.id = d.delivery_header_id
//						WHERE h.date <= :end_date AND h.branch_id = :branch_id
//					) delivery 
//				) ending_stock";

        return $sql;
    }

    public static function beginningBalanceLedger() {
        $sql = "SELECT (SUM(ja.debit) - SUM(ja.credit)) AS beginning_balance FROM
				
					" . JournalAccounting::model()->tableName() . "
				 ja
				INNER JOIN " . Account::model()->tableName() . " a ON a.id = ja.account_id
				WHERE a.id = :account_id AND ja.date < :start_date";

        return $sql;
    }

    public static function endBalanceLedger() {
        $sql = "SELECT (SUM(ja.debit) - SUM(ja.credit)) AS beginning_balance FROM
				
					" . JournalAccounting::model()->tableName() . "
				 ja
				INNER JOIN " . Account::model()->tableName() . " a ON a.id = ja.account_id
				WHERE a.id = :account_id AND ja.date <= :end_date";

        return $sql;
    }

    public static function endDebitLedger() {
        $sql = "SELECT SUM(ja.debit) AS beginning_debit 
				FROM " . JournalAccounting::model()->tableName() . " ja
				INNER JOIN " . Account::model()->tableName() . " a ON a.id = ja.account_id
				WHERE a.id = :account_id AND ja.date BETWEEN :start_date AND :end_date";

        return $sql;
    }

    public static function endCreditLedger() {
        $sql = "SELECT SUM(ja.credit) AS beginning_debit FROM
				
					" . JournalAccounting::model()->tableName() . "
				 ja
				INNER JOIN " . Account::model()->tableName() . " a ON a.id = ja.account_id
				WHERE a.id = :account_id AND ja.date BETWEEN :start_date AND :end_date";

        return $sql;
    }

}
