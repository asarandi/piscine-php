select abs(datediff(max(date), min(date))) as uptime from member_history;
