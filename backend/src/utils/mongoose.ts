import { Types } from 'mongoose';

export function oid(id: string | Types.ObjectId): Types.ObjectId;
export function oid(id: string | Types.ObjectId | null | undefined): Types.ObjectId | null | undefined {
  if (typeof id === 'string') {
    return Types.ObjectId.createFromHexString(id);
  }
  return id;
}

export function oidEq(id1: string | Types.ObjectId | null | undefined): (id2: string | Types.ObjectId | null | undefined) => boolean;
export function oidEq(id1: string | Types.ObjectId | null | undefined, id2: string | Types.ObjectId | null | undefined): boolean;
export function oidEq(
  id1: string | Types.ObjectId | null | undefined,
  id2?: string | Types.ObjectId | null,
): boolean | ((id2: string | Types.ObjectId | null | undefined) => boolean) {
  if (arguments.length === 1) {
    return (id2Curry) => oidEq(id1, id2Curry);
  }
  return !!(id1 && id2 && id1.toString() === id2.toString());
}
