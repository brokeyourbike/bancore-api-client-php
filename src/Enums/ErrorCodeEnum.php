<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Enums;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 *
 * @method static ErrorCodeEnum SUCCESS()
 * @method static ErrorCodeEnum PENDING()
 * @method static ErrorCodeEnum DUPLICATE_SESSION_ID()
 * @method static ErrorCodeEnum INVALID_SESSION_ID()
 * @method static ErrorCodeEnum ORDER_PARAMS_MISMATCH()
 * @method static ErrorCodeEnum ORDER_EXPIRED()
 * @method static ErrorCodeEnum ORDER_FAILED()
 * @method static ErrorCodeEnum POSSIBLE_DUPLICATE_REQUEST()
 * @method static ErrorCodeEnum REMIT_FAILED()
 * @method static ErrorCodeEnum SENDER_LIMIT_EXCEEDED()
 * @method static ErrorCodeEnum RECIPIENT_LIMIT_EXCEEDED()
 * @method static ErrorCodeEnum SENDER_AMOUNT_TOO_SMALL()
 * @method static ErrorCodeEnum SENDER_AMOUNT_TOO_BIG()
 * @method static ErrorCodeEnum SENDER_KYC_FAILED()
 * @method static ErrorCodeEnum RECIPIENT_KYC_FAILED()
 * @method static ErrorCodeEnum INVALID_ACCOUNT()
 * @method static ErrorCodeEnum PROVIDER_NOT_REACHABLE()
 * @method static ErrorCodeEnum INVALID_AMOUNT()
 * @method static ErrorCodeEnum UNABLE_TO_GET_FOREX_RATE()
 * @method static ErrorCodeEnum INVALID_IDENTIFIER()
 * @method static ErrorCodeEnum AUTH_FAILED()
 * @method static ErrorCodeEnum MERCHANT_VALIDATION_FAILED()
 * @method static ErrorCodeEnum INACTIVE_MERCHANT_STATUS()
 * @method static ErrorCodeEnum SENDER_CURRENCY_NOT_ENABLED()
 * @method static ErrorCodeEnum MISSING_REQUEST_PARAMS()
 * @method static ErrorCodeEnum INVALID_ENCRYPTION_KEY()
 * @method static ErrorCodeEnum INSUFFICIENT_FUNDS()
 * @method static ErrorCodeEnum INVALID_CARD_NUMBER()
 * @method static ErrorCodeEnum BLOCKED_CARD()
 * @method static ErrorCodeEnum BLOCKED_MOBILE_OR_BANK_ACCOUNT()
 * @method static ErrorCodeEnum INVALID_PIN()
 * @method static ErrorCodeEnum INVALID_CARD_TOKEN()
 * @method static ErrorCodeEnum FAILED_ATTEMPTS_EXCEEDED()
 * @method static ErrorCodeEnum INVALID_MERCHANT_IP()
 * @method static ErrorCodeEnum INVALID_FEE()
 * @method static ErrorCodeEnum MERCHANT_DAILY_LIMIT_EXCEEDED()
 * @method static ErrorCodeEnum MERCHANT_WEEKLY_LIMIT_EXCEEDED()
 * @method static ErrorCodeEnum MERCHANT_MONTHLY_LIMIT_EXCEEDED()
 * @method static ErrorCodeEnum QUOTA_NOT_FOUND()
 * @method static ErrorCodeEnum QUOTA_INVALID()
 * @method static ErrorCodeEnum SERVICE_NOT_CONFIGURED()
 * @method static ErrorCodeEnum SENDER_DAILY_LIMIT_EXCEEDED()
 * @method static ErrorCodeEnum NO_CONFIGURED_WALLET()
 * @method static ErrorCodeEnum RECIPINT_BANK_NOT_AVAILABLE()
 * @method static ErrorCodeEnum UNKNOWN_STATUS()
 * @method static ErrorCodeEnum TRANSFER_LIMIT_EXCEEDED_AT_PARTNER_INSTITUTION()
 * @method static ErrorCodeEnum SYSTEM_MALFUNCTION_AT_PARTNER_INSTITUTION()
 * @method static ErrorCodeEnum PARTNER_NOT_AVAILABLE()
 * @method static ErrorCodeEnum APPLICATION_ERROR()
 * @psalm-immutable
 */
final class ErrorCodeEnum extends \MyCLabs\Enum\Enum
{
    /**
     * Successful operation
     * remarks: Transaction is successful
     */
    private const SUCCESS = '1000';

    /**
     * Request Accepted, Status PENDING
     * remarks: Keep polling the transaction status endpoint until transaction status is successful/failed
     */
    private const PENDING = '1001';

    /**
     * Duplicate transaction/session ID
     * remarks: Resend transaction with a unique session/transaction ID
     */
    private const DUPLICATE_SESSION_ID = '1002';

    /**
     * Invalid transaction/session ID
     * remarks: Send transaction with a valid session/transaction ID
     */
    private const INVALID_SESSION_ID = '1003';

    /**
     * Order and Remit parameters do not match
     * remarks: Retry with valid order and remit parameters
     */
    private const ORDER_PARAMS_MISMATCH = '1004';

    /**
     * Expired order
     * remarks: Initiate a new order request and send order reference with remit request
     */
    private const ORDER_EXPIRED = '1005';

    /**
     * Failed to process order request
     * remarks: Operations team need to investigate
     */
    private const ORDER_FAILED = '1006';

    /**
     * Possible duplicate request
     * remarks: Wait for some minutes and retry transaction
     */
    private const POSSIBLE_DUPLICATE_REQUEST = '1007';

    /**
     * Remit failed
     * remarks: Operations team need to investigate
     */
    private const REMIT_FAILED = '1008';

    /**
     * Sender daily limit exceeded
     * remarks: Sender's daily limit has been exceeded
     */
    private const SENDER_LIMIT_EXCEEDED = '1009';

    /**
     * Recipient daily limit exceeded
     * remarks: Recipient's daily limit has been exceeded
     */
    private const RECIPIENT_LIMIT_EXCEEDED = '1010';

    /**
     * Sender minimum amount check failed
     * remarks: Sender's is sending less than the minimum amout configured
     */
    private const SENDER_AMOUNT_TOO_SMALL = '1011';

    /**
     * Sender maximum amount check failed
     * remarks: Sender's is sending more than the maximum amout configured
     */
    private const SENDER_AMOUNT_TOO_BIG = '1012';

    /**
     * Sender KYC check failed
     * remarks: KYC check failed for the sender
     */
    private const SENDER_KYC_FAILED = '1013';

    /**
     * Recipient KYC check failed
     * remarks: KYC check failed for the recipient
     */
    private const RECIPIENT_KYC_FAILED = '1014';

    /**
     * Account validation failed
     * remarks: Beneficiary's mobile wallet or bank account validation failed
     */
    private const INVALID_ACCOUNT = '1015';

    /**
     * Thirdparty provider not reachable
     * remarks: Wait for some minutes and retry transaction
     */
    private const PROVIDER_NOT_REACHABLE = '1016';

    /**
     * Invalid amount
     * remarks: Retry with a valid amount
     */
    private const INVALID_AMOUNT = '1017';

    /**
     * Unable to get Forex rate
     * remarks: Operations team need to investigate
     */
    private const UNABLE_TO_GET_FOREX_RATE = '1018';

    /**
     * Invalid identifier
     * remarks: Pass the correct identifier in your request
     */
    private const INVALID_IDENTIFIER = '1019';

    /**
     * Authentication failure
     * remarks: Authenticate with correct parameters
     */
    private const AUTH_FAILED = '1020';

    /**
     * Merchant validation failure
     * remarks: Reauthenticate and pass correct bearer token in your request header
     */
    private const MERCHANT_VALIDATION_FAILED = '1021';

    /**
     * Inactive merchant account status
     * remarks: Contact Bancore Switch support team
     */
    private const INACTIVE_MERCHANT_STATUS = '1022';

    /**
     * Sender currency not enabled
     * remarks: Contact Bancore Switch support team
     */
    private const SENDER_CURRENCY_NOT_ENABLED = '1023';

    /**
     * Missing request parameter(s)
     * remarks: Check your request and send again
     */
    private const MISSING_REQUEST_PARAMS = '1024';

    /**
     * Invalid encryption key
     * remarks: Contact Bancore Switch support team
     */
    private const INVALID_ENCRYPTION_KEY = '1025';

    /**
     * Insufficient balance
     * remarks: Top up your balance
     */
    private const INSUFFICIENT_FUNDS = '1026';

    /**
     * Invalid card number or account number already exist
     * remarks: Retry with valid card details
     */
    private const INVALID_CARD_NUMBER = '1027';

    /**
     * Blocked card
     * remarks: Contact Bancore Switch support team
     */
    private const BLOCKED_CARD = '1028';

    /**
     * Blocked mobile or bank account number
     * remarks: No further action
     */
    private const BLOCKED_MOBILE_OR_BANK_ACCOUNT = '1029';

    /**
     * Wrong PIN
     * remarks: Retry with correct PIN
     */
    private const INVALID_PIN = '1030';

    /**
     * Wrong card token
     * remarks: Retry with correct token
     */
    private const INVALID_CARD_TOKEN = '1031';

    /**
     * Exceeded failed attempt
     * remarks: Contact Bancore Switch support team
     */
    private const FAILED_ATTEMPTS_EXCEEDED = '1032';

    /**
     * Unknown merchant IP address
     * remarks: Send from a permitted IP or contact support
     */
    private const INVALID_MERCHANT_IP = '1033';

    /**
     * Fee value not available
     * remarks: Contact Bancore Switch support team
     */
    private const INVALID_FEE = '1034';

    /**
     * Merchant daily limit exceeded
     * remarks: Cancel and try the next day
     */
    private const MERCHANT_DAILY_LIMIT_EXCEEDED = '1035';

    /**
     * Merchant weekly limit exceeded
     * remarks: Cancel and try the next week
     */
    private const MERCHANT_WEEKLY_LIMIT_EXCEEDED = '1036';

    /**
     * Merchant monthly limit exceeded
     * remarks: Cancel and try the next month
     */
    private const MERCHANT_MONTHLY_LIMIT_EXCEEDED = '1037';

    /**
     * Order/quote does not exist
     * remarks: Send with valid quote reference
     */
    private const QUOTA_NOT_FOUND = '1038';

    /**
     * Quote does not belong to merchant
     * remarks: Send with correct merchant details
     */
    private const QUOTA_INVALID = '1039';

    /**
     * Class of service not configured
     * remarks: Contact Bancore Switch support team
     */
    private const SERVICE_NOT_CONFIGURED = '1040';

    /**
     * Sender daily velocity/transaction count exceeded
     * remarks: Cancel and try the next day
     */
    private const SENDER_DAILY_LIMIT_EXCEEDED = '1041';

    /**
     * No configured wallet for merchant
     * remarks: Contact Bancore Switch support team
     */
    private const NO_CONFIGURED_WALLET = '1042';

    /**
     * Beneficiary bank or mobile wallet operator not available
     */
    private const RECIPINT_BANK_NOT_AVAILABLE = '1044';

    /**
     * Status unknown. Poll for status
     */
    private const UNKNOWN_STATUS = '1053';

    /**
     * Transfer limit exceeded at partner institution.
     */
    private const TRANSFER_LIMIT_EXCEEDED_AT_PARTNER_INSTITUTION = '1054';

    /**
     * System malfunction at partner institution
     * remarks: Contact Bancore Switch support team
     */
    private const SYSTEM_MALFUNCTION_AT_PARTNER_INSTITUTION = '1055';

    /**
     * Partner institution not available
     * remarks: Contact Bancore Switch support team
     */
    private const PARTNER_NOT_AVAILABLE = '1098';

    /**
     * Application error
     * remarks: Wait for some minutes and retry transaction
     */
    private const APPLICATION_ERROR = '1099';
}
